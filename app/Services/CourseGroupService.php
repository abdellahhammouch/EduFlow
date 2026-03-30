<?php

namespace App\Services;

use App\Interfaces\Repositories\CourseGroupRepositoryInterface;
use Illuminate\Support\Collection;

class CourseGroupService
{
    public function __construct(
        private readonly CourseGroupRepositoryInterface $courseGroups,
    ) {
    }

    public function listByCourse(int $courseId): Collection
    {
        return $this->courseGroups->listByCourse($courseId);
    }

    public function showParticipants(int $courseId, int $groupId): ?array
    {
        $group = $this->courseGroups->listByCourse($courseId)
            ->firstWhere('id', $groupId);

        if ($group === null) {
            return null;
        }

        $participants = $group->enrollments
            ->where('status', 'active')
            ->values()
            ->map(fn ($enrollment) => [
                'enrollment_id' => $enrollment->id,
                'student_id' => $enrollment->student->id,
                'student_name' => $enrollment->student->name,
                'student_email' => $enrollment->student->email,
                'status' => $enrollment->status,
                'enrolled_at' => $enrollment->enrolled_at,
            ]);

        return [
            'group_id' => $group->id,
            'course_id' => $group->course_id,
            'group_number' => $group->group_number,
            'participants_count' => $participants->count(),
            'participants' => $participants,
        ];
    }
}
