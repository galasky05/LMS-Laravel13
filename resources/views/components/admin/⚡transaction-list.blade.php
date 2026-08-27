<?php

use App\Models\Enrollment;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $statusFilter = '';

    public function transactions()
    {
        return Enrollment::with(['user', 'course'])
            ->where('course_id', '!=', null)
            ->whereHas('course', fn($q) => $q->where('price', '>', 0))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);
    }
}
?>

<div class="p-6 space-y-6">
    <h2 class="text-xl font-bold" style="font-family:'Fraunces',serif;">Transaksi Pembayaran</h2>

    <select wire:model.live="statusFilter" class="border border-[#E4DFD2] rounded p-2">
        <option value="">Semua Status</option>
        <option value="pending">Pending</option>
        <option value="active">Active (Lunas)</option>
    </select>

    <div class="bg-white border border-[#E4DFD2] rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-[#F6F4EF]">
                <tr>
                    <th class="text-left p-3 text-xs uppercase text-[#8791A6]">Siswa</th>
                    <th class="text-left p-3 text-xs uppercase text-[#8791A6]">Course</th>
                    <th class="text-left p-3 text-xs uppercase text-[#8791A6]">Harga</th>
                    <th class="text-left p-3 text-xs uppercase text-[#8791A6]">Status</th>
                    <th class="text-left p-3 text-xs uppercase text-[#8791A6]">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->transactions() as $t)
                    <tr class="border-t border-[#E4DFD2]">
                        <td class="p-3 text-sm">{{ $t->user->name }}</td>
                        <td class="p-3 text-sm">{{ $t->course->title }}</td>
                        <td class="p-3 text-sm">Rp{{ number_format($t->course->price) }}</td>
                        <td class="p-3">
                            <span class="text-xs px-2 py-1 rounded {{ $t->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $t->status }}
                            </span>
                        </td>
                        <td class="p-3 text-sm text-[#8791A6]">{{ $t->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>{{ $this->transactions()->links() }}</div>
</div>