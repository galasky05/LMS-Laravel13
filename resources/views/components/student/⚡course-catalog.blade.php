<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Services\MidtransService;
use Livewire\Component;

new class extends Component {
    public $snapToken = null;
    public $payingEnrollmentId = null;

    public function courses()
    {
        return Course::where('is_published', true)->with('instructor')->latest()->get();
    }

    public function isEnrolled($courseId)
    {
        return Enrollment::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->exists();
    }

    public function getEnrollment($courseId)
    {
        return Enrollment::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->first();
    }

    public function enroll($courseId)
    {
        $course = Course::findOrFail($courseId);

        $enrollment = Enrollment::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'status' => $course->price > 0 ? 'pending' : 'active',
            'enrolled_at' => $course->price > 0 ? null : now(),
        ]);

        if ($course->price > 0) {
            $this->payForEnrollment($enrollment->id);
        } else {
            session()->flash('message', 'Berhasil enroll ke course ini!');
        }
    }

    public function payForEnrollment($enrollmentId)
    {
        $enrollment = Enrollment::with('course')->findOrFail($enrollmentId);

        $service = new MidtransService();
        $this->snapToken = $service->createSnapToken($enrollment, $enrollment->course, auth()->user());
        $this->payingEnrollmentId = $enrollmentId;

        $this->dispatch('open-snap', token: $this->snapToken, enrollmentId: $enrollmentId);
    }
};
?>

<div class="p-6 space-y-6">
    <h2 class="text-xl font-bold">Katalog Course</h2>

    @if (session('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded">{{ session('message') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($this->courses() as $course)
            <div class="bg-white p-4 rounded shadow space-y-2">
                <h3 class="font-bold">{{ $course->title }}</h3>
                <p class="text-sm text-gray-600">{{ $course->description }}</p>
                <p class="text-sm text-gray-500">Oleh: {{ $course->instructor->name }}</p>
                <p class="font-semibold">
                    {{ $course->price > 0 ? 'Rp' . number_format($course->price) : 'Gratis' }}
                </p>

                @if ($this->isEnrolled($course->id))
                    @php $enrollment = $this->getEnrollment($course->id); @endphp
                    @if ($enrollment->status === 'pending')
                        <button wire:click="payForEnrollment({{ $enrollment->id }})"
                            class="w-full bg-yellow-500 text-white px-4 py-2 rounded">
                            Bayar Sekarang
                        </button>
                    @else
                        <a href="{{ route('student.course.show', $course->id) }}"
                            class="block text-center bg-gray-200 text-gray-700 px-4 py-2 rounded">
                            Lanjut Belajar
                        </a>
                    @endif
                @else
                    <button wire:click="enroll({{ $course->id }})"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded">
                        Enroll
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div>
