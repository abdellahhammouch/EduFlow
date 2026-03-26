<?php

namespace App\Services;

use App\Interfaces\Repositories\EnrollmentRepositoryInterface;
use App\Models\Enrollment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LogicException;

class EnrollmentService
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly GroupAssignmentService $groupAssignment,
    ) {
    }

    public function create(array $data): Enrollment
    {
        return DB::transaction(function () use ($data): Enrollment {
            $existingEnrollment = $this->enrollments->findByStudentAndCourse(
                $data['student_id'],
                $data['course_id'],
            );

            if ($existingEnrollment !== null) {
                throw new LogicException('The student is already linked to this course.');
            }

            $group = $this->groupAssignment->assignToCourse($data['course_id']);

            return $this->enrollments->create([
                'student_id' => $data['student_id'],
                'course_id' => $data['course_id'],
                'payment_id' => $data['payment_id'],
                'course_group_id' => $group->id,
                'status' => $data['status'] ?? 'active',
                'enrolled_at' => $data['enrolled_at'] ?? now(),
            ]);
        });
    }

    public function listByCourse(int $courseId): Collection
    {
        return $this->enrollments->listByCourse($courseId);
    }
}
