<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;

class DashboardController extends Controller
{
    public function index()
    {
        $enrollments = auth()->user()->enrollments()
            ->where('status', 'active')
            ->with('course.lessons')
            ->get();

        $completedLessons = LessonProgress::where('user_id', auth()->id())
            ->where('is_completed', true)
            ->count();

        $avgScore = QuizAttempt::where('user_id', auth()->id())->avg('score');

        $stats = [
            'total_enrolled' => $enrollments->count(),
            'completed_lessons' => $completedLessons,
            'avg_quiz_score' => $avgScore ? round($avgScore) : 0,
        ];

        return view('student.dashboard', compact('stats', 'enrollments'));
    }
}