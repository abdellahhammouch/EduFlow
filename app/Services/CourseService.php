<?php

namespace App\Services;

use App\Interfaces\Repositories\CourseRepositoryInterface;
use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CourseService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courses,
    ) {
    }

    public function listAvailable(int $perPage = 15): LengthAwarePaginator
    {
        return $this->courses->paginateAvailable($perPage);
    }

    public function showAvailable(int $courseId): ?Course
    {
        return $this->courses->findAvailableById($courseId);
    }

    public function create(array $data): Course
    {
        return $this->courses->create($data);
    }

    public function update(Course $course, array $data): bool
    {
        return $this->courses->update($course, $data);
    }

    public function delete(Course $course): ?bool
    {
        return $this->courses->delete($course);
    }

    public function listByTeacher(int $teacherId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->courses->paginateByTeacher($teacherId, $perPage);
    }

    public function recommendForStudent(int $studentId, int $limit = 10): Collection
    {
        return $this->courses->recommendedForStudent($studentId, $limit);
    }
}
