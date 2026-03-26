<?php

namespace App\Services;

use App\Interfaces\Repositories\WishlistRepositoryInterface;
use App\Models\Wishlist;
use Illuminate\Support\Collection;

class WishlistService
{
    public function __construct(
        private readonly WishlistRepositoryInterface $wishlists,
    ) {
    }

    public function listByStudent(int $studentId): Collection
    {
        return $this->wishlists->listByStudent($studentId);
    }

    public function add(int $studentId, int $courseId): Wishlist
    {
        return $this->wishlists->add($studentId, $courseId);
    }

    public function remove(int $studentId, int $courseId): bool
    {
        return $this->wishlists->remove($studentId, $courseId);
    }
}
