<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl" style="font-family:'Fraunces',serif; color:#17233F;">Dashboard Instruktur</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-6 space-y-6">
        <p style="font-family:'IBM Plex Mono',monospace;" class="text-xs uppercase text-[#2F6F62]">Selamat datang, {{ auth()->user()->name }}</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Total Course</p>
                <p class="text-3xl font-semibold text-[#17233F] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['total_courses'] }}</p>
            </div>
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Published</p>
                <p class="text-3xl font-semibold text-[#2F6F62] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['published_courses'] }}</p>
            </div>
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Total Siswa</p>
                <p class="text-3xl font-semibold text-[#17233F] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['total_students'] }}</p>
            </div>
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Total Quiz</p>
                <p class="text-3xl font-semibold text-[#F2B705] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['total_quizzes'] }}</p>
            </div>
        </div>

        <div class="bg-white border border-[#E4DFD2] rounded-lg p-6">
            <h3 class="font-semibold mb-4" style="font-family:'Fraunces',serif; color:#17233F;">Course Terbaru</h3>
            @forelse($recentCourses as $course)
                <div class="flex justify-between items-center py-2 border-b border-[#E4DFD2] last:border-0">
                    <span class="text-sm text-[#17233F]">{{ $course->title }}</span>
                    <span class="text-xs px-2 py-1 rounded {{ $course->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $course->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-[#8791A6]">Belum ada course. <a href="{{ route('instructor.courses') }}" class="text-[#2F6F62] underline">Buat sekarang</a>.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>