<?php

namespace App\Services;

use App\Interfaces\Repositories\CourseRepositoryInterface;
use Illuminate\Support\Collection;

class TeacherStatsService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courses,
    ) {
    }

    public function courseStats(int $teacherId): Collection
    {
        return $this->courses->statsByTeacher($teacherId);
    }
}
