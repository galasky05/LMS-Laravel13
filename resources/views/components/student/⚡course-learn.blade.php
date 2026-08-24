<?php

use App\Models\Course;
use App\Models\LessonProgress;
use Livewire\Component;

new class extends Component {
    public Course $course;
    public $activeLesson = null;

    public function mount($courseId)
    {
        $enrolled = auth()->user()->enrollments()
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->exists();

        if (!$enrolled) {
            abort(403, 'Kamu belum enroll (atau belum bayar) course ini.');
        }

        $this->course = Course::with('lessons')->findOrFail($courseId);
        $this->activeLesson = $this->course->lessons->first()?->id;
    }

    public function selectLesson($lessonId)
    {
        $this->activeLesson = $lessonId;
    }

    public function isCompleted($lessonId)
    {
        return LessonProgress::where('user_id', auth()->id())
            ->where('lesson_id', $lessonId)
            ->where('is_completed', true)
            ->exists();
    }

    public function markComplete($lessonId)
    {
        LessonProgress::updateOrCreate(
            ['user_id' => auth()->id(), 'lesson_id' => $lessonId],
            ['is_completed' => true, 'completed_at' => now()]
        );
    }

    public function progressPercent()
    {
        $total = $this->course->lessons->count();
        if ($total === 0) return 0;

        $done = LessonProgress::where('user_id', auth()->id())
            ->whereIn('lesson_id', $this->course->lessons->pluck('id'))
            ->where('is_completed', true)
            ->count();

        return round(($done / $total) * 100);
    }
}
?>

<div class="p-6">
    <h2 class="text-xl font-bold mb-2">{{ $course->title }}</h2>

    <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $this->progressPercent() }}%"></div>
    </div>
    <p class="text-sm text-gray-600 mb-4">Progress: {{ $this->progressPercent() }}%</p>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-1 bg-white rounded shadow p-4 space-y-2">
            @foreach($course->lessons as $lesson)
                <button wire:click="selectLesson({{ $lesson->id }})"
                    class="block w-full text-left p-2 rounded {{ $activeLesson == $lesson->id ? 'bg-blue-100' : '' }}">
                    {{ $this->isCompleted($lesson->id) ? '✅' : '⬜' }} {{ $lesson->title }}
                </button>
            @endforeach
        </div>

        <div class="md:col-span-3 bg-white rounded shadow p-4">
            @php $lesson = $course->lessons->firstWhere('id', $activeLesson); @endphp

            @if($lesson)
                <h3 class="font-bold text-lg mb-2">{{ $lesson->title }}</h3>

                @if($lesson->video_url)
                    <div class="aspect-video mb-4">
                        <iframe class="w-full h-full" src="{{ str_replace('watch?v=', 'embed/', $lesson->video_url) }}" allowfullscreen></iframe>
                    </div>
                @endif

                <p class="mb-4">{{ $lesson->content }}</p>

                @if(!$this->isCompleted($lesson->id))
                    <button wire:click="markComplete({{ $lesson->id }})" class="bg-green-600 text-white px-4 py-2 rounded">
                        Tandai Selesai
                    </button>
                @else
                    <span class="text-green-600 font-semibold">Lesson selesai ✅</span>
                @endif
            @endif
        </div>
    </div>
</div>