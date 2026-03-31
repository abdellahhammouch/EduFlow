<?php

namespace App\Repositories;

use App\Interfaces\Repositories\CourseRepositoryInterface;
use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
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

    public function statsByTeacher(int $teacherId): Collection
    {
        return Course::query()
            ->leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->leftJoin('payments', 'courses.id', '=', 'payments.course_id')
            ->leftJoin('course_groups', 'courses.id', '=', 'course_groups.course_id')
            ->where('courses.teacher_id', $teacherId)
            ->where('courses.status', 'active')
            ->select([
                'courses.id',
                'courses.title',
                'courses.price',
                'courses.domain_id',
                DB::raw('COUNT(DISTINCT CASE WHEN enrollments.status = "active" THEN enrollments.id END) as active_enrollments_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN enrollments.status = "withdrawn" THEN enrollments.id END) as withdrawn_enrollments_count'),
                DB::raw('COUNT(DISTINCT course_groups.id) as groups_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN payments.status = "succeeded" THEN payments.id END) as succeeded_payments_count'),
                DB::raw('COALESCE(SUM(DISTINCT CASE WHEN payments.status = "succeeded" THEN payments.amount END), 0) as revenue'),
            ])
            ->groupBy('courses.id', 'courses.title', 'courses.price', 'courses.domain_id')
            ->orderBy('courses.created_at', 'desc')
            ->get();
    }
}
