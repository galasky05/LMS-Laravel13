<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function download($courseId)
    {
        $course = Course::with(['lessons', 'quizzes', 'instructor'])->findOrFail($courseId);
        $user = auth()->user();

        $enrolled = $user->enrollments()
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->exists();

        if (!$enrolled) {
            abort(403, 'Kamu belum enroll di course ini.');
        }

        // Cek semua lesson selesai
        $totalLessons = $course->lessons->count();
        $completedLessons = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->where('is_completed', true)
            ->count();

        $allLessonsDone = $totalLessons > 0 && $completedLessons === $totalLessons;

        // Cek semua quiz (kalau ada) sudah lulus minimal sekali
        $allQuizzesPassed = true;
        foreach ($course->quizzes as $quiz) {
            $passed = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('is_passed', true)
                ->exists();
            if (!$passed) {
                $allQuizzesPassed = false;
                break;
            }
        }

        if (!$allLessonsDone || !$allQuizzesPassed) {
            abort(403, 'Kamu belum menyelesaikan semua lesson dan/atau lulus semua quiz di course ini.');
        }

        $pdf = Pdf::loadView('certificate.template', [
            'course' => $course,
            'user' => $user,
            'date' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat - ' . $course->title . '.pdf');
    }
}