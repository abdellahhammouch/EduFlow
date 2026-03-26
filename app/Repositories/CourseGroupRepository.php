<?php

namespace App\Repositories;

use App\Interfaces\Repositories\CourseGroupRepositoryInterface;
use App\Models\CourseGroup;
use Illuminate\Support\Collection;

class CourseGroupRepository implements CourseGroupRepositoryInterface
{
    public function listByCourse(int $courseId): Collection
    {
        return CourseGroup::query()
            ->with(['enrollments.student'])
            ->where('course_id', $courseId)
            ->orderBy('group_number')
            ->get();
    }

    public function findAvailableForCourse(int $courseId, int $capacity = 25): ?CourseGroup
    {
        return CourseGroup::query()
            ->where('course_id', $courseId)
            ->withCount([
                'enrollments as active_enrollments_count' => fn ($query) => $query->where('status', 'active'),
            ])
            ->having('active_enrollments_count', '<', $capacity)
            ->orderBy('group_number')
            ->first();
    }

    public function nextGroupNumber(int $courseId): int
    {
        return (int) CourseGroup::query()
            ->where('course_id', $courseId)
            ->max('group_number') + 1;
    }

    public function create(array $data): CourseGroup
    {
        return CourseGroup::query()->create($data);
    }
}
