<?php

namespace App\Repositories;

use App\Interfaces\Repositories\EnrollmentRepositoryInterface;
use App\Models\Enrollment;
use Illuminate\Support\Collection;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function create(array $data): Enrollment
    {
        return Enrollment::query()->create($data);
    }

    public function findByStudentAndCourse(int $studentId, int $courseId): ?Enrollment
    {
        return Enrollment::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function listByCourse(int $courseId): Collection
    {
        return Enrollment::query()
            ->with(['student', 'courseGroup'])
            ->where('course_id', $courseId)
            ->latest()
            ->get();
    }

    public function update(Enrollment $enrollment, array $data): bool
    {
        return $enrollment->update($data);
    }
}
