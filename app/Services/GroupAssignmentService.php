<?php

namespace App\Services;

use App\Interfaces\Repositories\CourseGroupRepositoryInterface;
use App\Models\CourseGroup;

class GroupAssignmentService
{
    public function __construct(
        private readonly CourseGroupRepositoryInterface $courseGroups,
    ) {
    }

    public function assignToCourse(int $courseId): CourseGroup
    {
        $availableGroup = $this->courseGroups->findAvailableForCourse($courseId);

        if ($availableGroup !== null) {
            return $availableGroup;
        }

        return $this->courseGroups->create([
            'course_id' => $courseId,
            'group_number' => $this->courseGroups->nextGroupNumber($courseId),
        ]);
    }
}
