<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseGroup;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnrollmentSeeder extends Seeder
{
    /**
     * Seed wishlists, payments, groups, and enrollments.
     */
    public function run(): void
    {
        $students = User::query()->where('role', 'student')->get();
        $courses = Course::query()->where('status', 'active')->get();

        if ($students->isEmpty() || $courses->isEmpty()) {
            return;
        }

        foreach ($students as $student) {
            $wishlistCourses = $courses->whereNotIn('id', $student->enrollments()->pluck('course_id'))->random(rand(1, min(3, $courses->count())));

            foreach ($wishlistCourses as $course) {
                Wishlist::query()->firstOrCreate([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                ]);
            }
        }

        $primaryCourse = $courses->first();
        $primaryStudents = $students->take(min(27, $students->count()));

        foreach ($primaryStudents->values() as $index => $student) {
            $this->createEnrollment($student, $primaryCourse, $index + 1);
        }

        foreach ($courses->skip(1) as $course) {
            $courseStudents = $students->shuffle()->take(rand(4, min(10, $students->count())));

            foreach ($courseStudents as $student) {
                $alreadyEnrolled = Enrollment::query()
                    ->where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists();

                if (! $alreadyEnrolled) {
                    $sequence = Enrollment::query()->where('course_id', $course->id)->count() + 1;
                    $this->createEnrollment($student, $course, $sequence);
                }
            }
        }

        Enrollment::query()
            ->whereIn('student_id', $primaryStudents->take(2)->pluck('id'))
            ->where('course_id', $primaryCourse->id)
            ->get()
            ->each(function (Enrollment $enrollment): void {
                $enrollment->update([
                    'status' => 'withdrawn',
                    'withdrawn_at' => now()->subDays(rand(1, 5)),
                ]);
            });
    }

    /**
     * Create a payment and enrollment for the given student and course.
     */
    private function createEnrollment(User $student, Course $course, int $sequence): void
    {
        $groupNumber = (int) ceil($sequence / 25);

        $group = CourseGroup::query()->firstOrCreate(
            [
                'course_id' => $course->id,
                'group_number' => $groupNumber,
            ],
        );

        $payment = Payment::query()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'stripe_payment_intent_id' => 'pi_' . Str::lower(Str::random(24)),
            'amount' => $course->price,
            'currency' => 'usd',
            'status' => 'succeeded',
            'paid_at' => now()->subDays(rand(1, 10)),
        ]);

        Enrollment::query()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'payment_id' => $payment->id,
            'course_group_id' => $group->id,
            'status' => 'active',
            'enrolled_at' => $payment->paid_at ?? now(),
        ]);
    }
}
