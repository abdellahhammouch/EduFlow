<?php

namespace App\Interfaces\Repositories;

use App\Models\CourseGroup;
use Illuminate\Support\Collection;

interface CourseGroupRepositoryInterface
{
    public function listByCourse(int $courseId): Collection;

    public function findAvailableForCourse(int $courseId, int $capacity = 25): ?CourseGroup;

    public function nextGroupNumber(int $courseId): int;

    public function create(array $data): CourseGroup;
}
