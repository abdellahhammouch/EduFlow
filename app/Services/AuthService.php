<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use App\Interfaces\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LogicException;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {
    }

    /**
     * Register a user and immediately issue a JWT.
     *
     * @return array<string, mixed>
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data): array {
            $user = $this->users->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            if ($user->role === 'student' && ! empty($data['domain_ids'])) {
                $user->interestedDomains()->sync($data['domain_ids']);
            }

            $user->load('interestedDomains');

            return $this->buildAuthPayload(auth('api')->login($user), $user);
        });
    }

    /**
     * Attempt to authenticate a user and return a JWT payload.
     *
     * @return array<string, mixed>
     */
    public function login(array $credentials): array
    {
        $token = auth('api')->attempt($credentials);

        if ($token === false) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        return $this->buildAuthPayload($token, $user->load('interestedDomains'));
    }

    /**
     * Return the currently authenticated user payload.
     *
     * @return array<string, mixed>
     */
    public function me(): array
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        return [
            'user' => $user->load('interestedDomains'),
        ];
    }

    /**
     * Invalidate the current token.
     */
    public function logout(): void
    {
        auth('api')->logout();
    }

    /**
     * Refresh the current token.
     *
     * @return array<string, mixed>
     */
    public function refresh(): array
    {
        $token = auth('api')->refresh();

        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        return $this->buildAuthPayload($token, $user->load('interestedDomains'));
    }

    /**
     * Send a password reset link to the given email.
     */
    public function sendPasswordResetLink(string $email): void
    {
        $status = Password::sendResetLink([
            'email' => $email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }

    /**
     * Reset the user's password using the token sent by Laravel.
     */
    public function resetPassword(array $data): void
    {
        $status = Password::reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $data['token'],
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new LogicException(__($status));
        }
    }

    /**
     * Normalize the authentication response body.
     *
     * @return array<string, mixed>
     */
    private function buildAuthPayload(string $token, object $user): array
    {
        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }
}
