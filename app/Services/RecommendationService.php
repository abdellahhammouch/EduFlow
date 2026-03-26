<?php

namespace App\Services;

use App\Interfaces\Repositories\CourseRepositoryInterface;
use Illuminate\Support\Collection;

class RecommendationService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courses,
    ) {
    }

    public function forStudent(int $studentId, int $limit = 10): Collection
    {
        return $this->courses->recommendedForStudent($studentId, $limit);
    }
}
