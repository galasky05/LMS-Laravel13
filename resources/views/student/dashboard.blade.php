<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl" style="font-family:'Fraunces',serif; color:#17233F;">Dashboard Siswa</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-6 space-y-6">
        <p style="font-family:'IBM Plex Mono',monospace;" class="text-xs uppercase text-[#2F6F62]">Selamat datang, {{ auth()->user()->name }}</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Course Diikuti</p>
                <p class="text-3xl font-semibold text-[#17233F] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['total_enrolled'] }}</p>
            </div>
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Lesson Selesai</p>
                <p class="text-3xl font-semibold text-[#2F6F62] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['completed_lessons'] }}</p>
            </div>
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Rata-rata Skor Quiz</p>
                <p class="text-3xl font-semibold text-[#F2B705] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['avg_quiz_score'] }}%</p>
            </div>
        </div>

        <div class="bg-white border border-[#E4DFD2] rounded-lg p-6">
            <h3 class="font-semibold mb-4" style="font-family:'Fraunces',serif; color:#17233F;">Course Kamu</h3>
            @forelse($enrollments as $enrollment)
                @php
                    $total = $enrollment->course->lessons->count();
                    $done = \App\Models\LessonProgress::where('user_id', auth()->id())
                        ->whereIn('lesson_id', $enrollment->course->lessons->pluck('id'))
                        ->where('is_completed', true)->count();
                    $percent = $total > 0 ? round(($done / $total) * 100) : 0;
                @endphp
                <div class="py-3 border-b border-[#E4DFD2] last:border-0">
                    <div class="flex justify-between mb-1">
                        <a href="{{ route('student.course.show', $enrollment->course->id) }}" class="text-sm font-medium text-[#17233F] hover:text-[#2F6F62]">
                            {{ $enrollment->course->title }}
                        </a>
                        <span class="text-xs text-[#8791A6]">{{ $percent }}%</span>
                    </div>
                    <div class="w-full bg-[#E4DFD2] rounded-full h-1.5">
                        <div class="bg-[#2F6F62] h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#8791A6]">Belum ada course. <a href="{{ route('student.catalog') }}" class="text-[#2F6F62] underline">Cari course</a>.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>