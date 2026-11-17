package main

import (
	"bytes"
	"context"
	"encoding/json"
	"fmt"
	"image"
	"io"
	"log"
	"math/rand"
	"mime/multipart"
	"net/http"
	"os"
	"sync"
	"time"

	"github.com/aws/aws-sdk-go-v2/aws"
	"github.com/aws/aws-sdk-go-v2/config"
	"github.com/aws/aws-sdk-go-v2/service/s3"
	"github.com/deepteams/webp"
	"github.com/disintegration/imaging"
	"github.com/joho/godotenv"
)

var s3Client *s3.Client

type Response struct {
	Status   string `json:"status"`
	Filename string `json:"filename,omitempty"`
}

type imageVariant struct {
	label        string
	fullFileName string
	img          image.Image
}

func main() {
	if err := godotenv.Load(); err != nil {
		log.Println("Файл .env не найден использую системные переменные")
	}

	initS3()

	http.HandleFunc("/ping", ping)
	http.HandleFunc("/process", process)

	log.Println("Сервер запущен на порте 8080")
	if err := http.ListenAndServe(":8080", nil); err != nil {
		log.Fatalf("Ошибка запуска сервера: %v", err)
	}
}

// Инициализация подключения к S3 Yandex
func initS3() {
	ctx := context.Background()

	cfg, err := config.LoadDefaultConfig(ctx,
		config.WithRegion(os.Getenv("AWS_DEFAULT_REGION")),
	)
	if err != nil {
		log.Fatalf("Ошибка загрузки конфига AWS: %v", err)
	}

	s3Client = s3.NewFromConfig(cfg, func(o *s3.Options) {
		o.BaseEndpoint = aws.String("https://storage.yandexcloud.net")
		o.UsePathStyle = true
	})

	log.Println("S3 клиент успешно инициализирован")
}

// Проверка работы сервера
func ping(w http.ResponseWriter, r *http.Request) {
	sendJSON(w, http.StatusOK, Response{
		Status: "ok",
	})
}

// Обработка фото
func process(w http.ResponseWriter, r *http.Request) {
	// Проверка приходящего типа от сервера
	if r.Method != http.MethodPost {
		http.Error(w, "Разрешен только метод POST", http.StatusMethodNotAllowed)
		return
	}

	r.Body = http.MaxBytesReader(w, r.Body, 10<<20)

	if err := r.ParseMultipartForm(10 << 20); err != nil {
		http.Error(w, "Ошибка парсинга формы: "+err.Error(), http.StatusBadRequest)
		return
	}

	// Достем тип сущности, для которой сохраняем фото (нужно product или category)
	entityType := r.FormValue("entity_type")
	if entityType != "product" && entityType != "category" {
		http.Error(w, "Неверный тип сущности", http.StatusBadRequest)
		return
	}

	// Получаем картинку
	file, _, err := r.FormFile("img")
	if err != nil {
		http.Error(w, "Ошибка получения изображения: "+err.Error(), http.StatusBadRequest)
		return
	}
	defer file.Close()

	// Валидируем картинку (если вдруг придет файл)
	if err := validateImage(file); err != nil {
		http.Error(w, "Проблема с форматом полученного файла: "+err.Error(), http.StatusBadRequest)
		return
	}

	// Генерируем название
	uniqueID := fmt.Sprintf("%d%d", time.Now().Unix(), rand.Intn(10000))
	newFileName := fmt.Sprintf("%s_%s", entityType, uniqueID)

	srcImage, err := imaging.Decode(file, imaging.AutoOrientation(true))
	if err != nil {
		http.Error(w, "Ошибка декодирования картинки", http.StatusBadRequest)
		return
	}

	ctx, cancel := context.WithTimeout(r.Context(), 15*time.Second)
	defer cancel()

	bucket := os.Getenv("S3_BUCKET_NAME")

	// Кодируем в webp и сохраняем в S3 Yandex
	encodeAndUpload := func(img image.Image, fileName string) error {
		var buf bytes.Buffer
		if err := webp.Encode(&buf, img, &webp.Options{Lossless: false, Quality: 80}); err != nil {
			return fmt.Errorf("ошибка кодирования %s: %w", fileName, err)
		}

		_, err := s3Client.PutObject(ctx, &s3.PutObjectInput{
			Bucket:      aws.String(bucket),
			Key:         aws.String(fileName),
			Body:        bytes.NewReader(buf.Bytes()),
			ContentType: aws.String("image/webp"),
		})
		if err != nil {
			return fmt.Errorf("ошибка загрузки %s в S3: %w", fileName, err)
		}

		return nil
	}

	// Добавляем название формата и генерируем названия для большой и маленькой фоток +
	// префикс папки и размеры фото
	originalFileName := newFileName + ".webp"
	largeFileName := newFileName + "_large.webp"
	smallFileName := newFileName + "_small.webp"

	folderPrefix := "products/"
	largeWidth := 1200
	smallWidth := 400

	// Если сущность == категория то меняем префикс папки и размеры фото
	if entityType == "category" {
		folderPrefix = "categories/"
		largeWidth = 800
		smallWidth = 200
	}

	largeImg := resizeIfNeeded(srcImage, largeWidth)
	smallImg := resizeIfNeeded(srcImage, smallWidth)

	// Данные для кодирования и сохранения в S3 Yandex
	variants := []imageVariant{
		{label: "оригинала", fullFileName: folderPrefix + originalFileName, img: srcImage},
		{label: "large версии", fullFileName: folderPrefix + largeFileName, img: largeImg},
		{label: "small версии", fullFileName: folderPrefix + smallFileName, img: smallImg},
	}

	// Горутины для того чтобы сохранять одновременно 3 фото.
	// Если 1 фото не загрузится, остальные удалятся
	var (
		wg            sync.WaitGroup
		mu            sync.Mutex
		uploadedFiles []string
		firstErr      error
		errLabel      string
	)

	for _, variant := range variants {
		wg.Add(1)

		go func(variant imageVariant) {
			defer wg.Done()

			if err := encodeAndUpload(variant.img, variant.fullFileName); err != nil {
				mu.Lock()
				if firstErr == nil {
					firstErr = err
					errLabel = variant.label
					cancel()
				}
				mu.Unlock()
			} else {
				mu.Lock()
				uploadedFiles = append(uploadedFiles, variant.fullFileName)
				mu.Unlock()
			}
		}(variant)
	}

	wg.Wait()

	if firstErr != nil {
		log.Printf("Ошибка при обработке %s: %v", errLabel, firstErr)

		cleanUpCtx, cleanUpCancel := context.WithTimeout(context.Background(), 15*time.Second)
		defer cleanUpCancel()

		for _, fileToDelete := range uploadedFiles {
			_, err := s3Client.DeleteObject(cleanUpCtx, &s3.DeleteObjectInput{
				Bucket: aws.String(bucket),
				Key:    aws.String(fileToDelete),
			})

			if err != nil {
				log.Printf("Не удалось удалить файл %s при откате: %v", fileToDelete, err)
			} else {
				log.Printf("Файл %s успешно удален при откате", fileToDelete)
			}
		}

		http.Error(w, "Ошибка обработки "+errLabel, http.StatusInternalServerError)
		return
	}

	// Передаем на бэк успешный статус и название оригинальной фотки
	sendJSON(w, http.StatusOK, Response{
		Status:   "success",
		Filename: folderPrefix + originalFileName,
	})
}

// Проверка типа
func validateImage(file multipart.File) error {
	buf := make([]byte, 512)

	n, err := file.Read(buf)
	if err != nil && err != io.EOF {
		return fmt.Errorf("Не удалось прочитать файл: %w", err)
	}

	contentType := http.DetectContentType(buf[:n])
	switch contentType {
	case "image/jpeg", "image/png", "image/webp":
		if _, err := file.Seek(0, io.SeekStart); err != nil {
			return fmt.Errorf("Ошибка сброса позиции файла: %w", err)
		}
		return nil
	default:
		return fmt.Errorf("Недопустимый формат файла: %s", contentType)
	}
}

// Изменение размера, если ширина картинки больше требуемой
func resizeIfNeeded(img image.Image, maxWidth int) image.Image {
	if img.Bounds().Dx() <= maxWidth {
		return img
	}
	return imaging.Resize(img, maxWidth, 0, imaging.Lanczos)
}

// Генерация JSON ответа
func sendJSON(w http.ResponseWriter, status int, data any) {
	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(status)
	json.NewEncoder(w).Encode(data)
}
