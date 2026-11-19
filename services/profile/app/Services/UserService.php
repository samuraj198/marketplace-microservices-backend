<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    public function register(array $data): array
    {
        try {
            return DB::transaction(function () use ($data) {
                $user = User::create($data);
                $token = JWTAuth::fromUser($user);

                return [$user, $token];
            });
        } catch (JWTException $e) {
            throw new HttpException(500, 'Не удалось совершить регистрацию, попробуйте позже', $e);
        }
    }

    public function auth(array $data): array
    {
        try {
            if (!$token = JWTAuth::attempt($data)) {
                throw new AuthenticationException('Неверные данные пользователя');
            }
            $user = auth('api')->user();

            return [$user, $token];
        } catch (JWTException $e) {
            throw new HttpException(500, 'Не удалось совершить авторизацию, попробуйте позже', $e);
        }
    }

    public function me(): User
    {
        /** @var User|null $user */
        $user = auth('api')->user();

        if ($user === null) {
            throw new AuthenticationException('Пользователь не авторизован');
        }

        return $user;
    }

    public function logout(): void
    {
        try {
            auth('api')->logout();
        } catch (JWTException $e) {
            throw new HttpException(500, 'Не удалось выйти из системы, попробуйте позже', $e);
        }
    }
}
