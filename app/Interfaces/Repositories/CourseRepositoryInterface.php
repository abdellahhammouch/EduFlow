<?php

namespace App\Interfaces\Repositories;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CourseRepositoryInterface
{
    public function paginateAvailable(int $perPage = 15): LengthAwarePaginator;

    public function findAvailableById(int $courseId): ?Course;

    public function create(array $data): Course;

    public function update(Course $course, array $data): bool;

    public function delete(Course $course): ?bool;

    public function paginateByTeacher(int $teacherId, int $perPage = 15): LengthAwarePaginator;

    public function recommendedForStudent(int $studentId, int $limit = 10): Collection;
}
