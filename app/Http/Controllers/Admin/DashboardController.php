<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_courses' => Course::count(),
            'total_revenue' => Enrollment::where('status', 'active')
                ->join('courses', 'courses.id', '=', 'enrollments.course_id')
                ->sum('courses.price'),
        ];

        $recentEnrollments = Enrollment::with('user', 'course')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentEnrollments'));
    }
}