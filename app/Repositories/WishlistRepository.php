<?php

namespace App\Repositories;

use App\Interfaces\Repositories\WishlistRepositoryInterface;
use App\Models\Wishlist;
use Illuminate\Support\Collection;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function listByStudent(int $studentId): Collection
    {
        return Wishlist::query()
            ->with('course.domain')
            ->where('student_id', $studentId)
            ->latest()
            ->get();
    }

    public function exists(int $studentId, int $courseId): bool
    {
        return Wishlist::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->exists();
    }

    public function add(int $studentId, int $courseId): Wishlist
    {
        return Wishlist::query()->firstOrCreate([
            'student_id' => $studentId,
            'course_id' => $courseId,
        ]);
    }

    public function remove(int $studentId, int $courseId): bool
    {
        return Wishlist::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->delete() > 0;
    }
}
