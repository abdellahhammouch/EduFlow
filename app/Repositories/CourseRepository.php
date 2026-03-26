<?php

namespace App\Repositories;

use App\Interfaces\Repositories\CourseRepositoryInterface;
use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function paginateAvailable(int $perPage = 15): LengthAwarePaginator
    {
        return Course::query()
            ->with(['teacher', 'domain'])
            ->where('status', 'active')
            ->latest()
            ->paginate($perPage);
    }

    public function findAvailableById(int $courseId): ?Course
    {
        return Course::query()
            ->with(['teacher', 'domain'])
            ->where('status', 'active')
            ->find($courseId);
    }

    public function create(array $data): Course
    {
        return Course::query()->create($data);
    }

    public function update(Course $course, array $data): bool
    {
        return $course->update($data);
    }

    public function delete(Course $course): ?bool
    {
        $course->status = 'deleted';
        $course->save();

        return $course->delete();
    }

    public function paginateByTeacher(int $teacherId, int $perPage = 15): LengthAwarePaginator
    {
        return Course::query()
            ->with('domain')
            ->where('teacher_id', $teacherId)
            ->latest()
            ->paginate($perPage);
    }

    public function recommendedForStudent(int $studentId, int $limit = 10): Collection
    {
        return Course::query()
            ->with(['teacher', 'domain'])
            ->where('status', 'active')
            ->whereIn('domain_id', function ($query) use ($studentId): void {
                $query->select('domain_id')
                    ->from('student_domain')
                    ->where('student_id', $studentId);
            })
            ->latest()
            ->limit($limit)
            ->get();
    }
}
