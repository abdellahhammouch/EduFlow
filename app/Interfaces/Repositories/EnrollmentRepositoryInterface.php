<?php

namespace App\Interfaces\Repositories;

use App\Models\Enrollment;
use Illuminate\Support\Collection;

interface EnrollmentRepositoryInterface
{
    public function create(array $data): Enrollment;

    public function findByStudentAndCourse(int $studentId, int $courseId): ?Enrollment;

    public function listByStudent(int $studentId): Collection;

    public function listByCourse(int $courseId): Collection;

    public function update(Enrollment $enrollment, array $data): bool;
}
