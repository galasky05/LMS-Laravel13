<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $courses = Course::where('instructor_id', auth()->id())->get();
        $courseIds = $courses->pluck('id');

        $stats = [
            'total_courses' => $courses->count(),
            'published_courses' => $courses->where('is_published', true)->count(),
            'total_students' => Enrollment::whereIn('course_id', $courseIds)
                ->where('status', 'active')
                ->distinct('user_id')
                ->count('user_id'),
            'total_quizzes' => Quiz::whereIn('course_id', $courseIds)->count(),
        ];

        $recentCourses = $courses->sortByDesc('created_at')->take(5);

        return view('instructor.dashboard', compact('stats', 'recentCourses'));
    }
}