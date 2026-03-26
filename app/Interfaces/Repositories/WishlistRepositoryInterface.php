<?php

namespace App\Interfaces\Repositories;

use App\Models\Wishlist;
use Illuminate\Support\Collection;

interface WishlistRepositoryInterface
{
    public function listByStudent(int $studentId): Collection;

    public function exists(int $studentId, int $courseId): bool;

    public function add(int $studentId, int $courseId): Wishlist;

    public function remove(int $studentId, int $courseId): bool;
}
