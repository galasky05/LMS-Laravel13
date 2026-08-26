<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl" style="font-family:'Fraunces',serif; color:#17233F;">Dashboard Admin</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-6 space-y-6">
        <p style="font-family:'IBM Plex Mono',monospace;" class="text-xs uppercase text-[#2F6F62]">Selamat datang, {{ auth()->user()->name }}</p>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Total User</p>
                <p class="text-2xl font-semibold text-[#17233F] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Instruktur</p>
                <p class="text-2xl font-semibold text-[#2F6F62] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['total_instructors'] }}</p>
            </div>
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Siswa</p>
                <p class="text-2xl font-semibold text-[#17233F] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['total_students'] }}</p>
            </div>
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Course</p>
                <p class="text-2xl font-semibold text-[#17233F] mt-1" style="font-family:'Fraunces',serif;">{{ $stats['total_courses'] }}</p>
            </div>
            <div class="bg-white border border-[#E4DFD2] rounded-lg p-5">
                <p class="text-xs uppercase text-[#8791A6]" style="font-family:'IBM Plex Mono',monospace;">Revenue</p>
                <p class="text-xl font-semibold text-[#F2B705] mt-1" style="font-family:'Fraunces',serif;">Rp{{ number_format($stats['total_revenue']) }}</p>
            </div>
        </div>

        <div class="bg-white border border-[#E4DFD2] rounded-lg p-6">
            <h3 class="font-semibold mb-4" style="font-family:'Fraunces',serif; color:#17233F;">Enrollment Terbaru</h3>
            @forelse($recentEnrollments as $enrollment)
                <div class="flex justify-between items-center py-2 border-b border-[#E4DFD2] last:border-0 text-sm">
                    <span class="text-[#17233F]">{{ $enrollment->user->name }} &rarr; {{ $enrollment->course->title }}</span>
                    <span class="text-xs px-2 py-1 rounded {{ $enrollment->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $enrollment->status }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-[#8791A6]">Belum ada enrollment.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>