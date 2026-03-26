<?php

namespace App\Services;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LogicException;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {
    }

    public function register(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = $this->users->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            if ($user->role === 'student' && ! empty($data['domain_ids'])) {
                $user->interestedDomains()->sync($data['domain_ids']);
            }

            return $user->load('interestedDomains');
        });
    }

    public function login(array $credentials): never
    {
        unset($credentials);

        throw new LogicException('JWT authentication will be wired in the next step.');
    }
}
