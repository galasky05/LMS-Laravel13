<?php

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';

    public function users()
    {
        return User::where('id', '!=', auth()->id())
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);
    }

    public function changeRole($userId, $newRole)
    {
        User::where('id', $userId)->update(['role' => $newRole]);
    }

    public function deleteUser($userId)
    {
        User::where('id', $userId)->delete();
    }
}
?>

<div class="p-6 space-y-6">
    <h2 class="text-xl font-bold" style="font-family:'Fraunces',serif;">Kelola User</h2>

    <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." class="border border-[#E4DFD2] rounded p-2 w-full max-w-sm">

    <div class="bg-white border border-[#E4DFD2] rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-[#F6F4EF]">
                <tr>
                    <th class="text-left p-3 text-xs uppercase text-[#8791A6]">Nama</th>
                    <th class="text-left p-3 text-xs uppercase text-[#8791A6]">Email</th>
                    <th class="text-left p-3 text-xs uppercase text-[#8791A6]">Role</th>
                    <th class="text-left p-3 text-xs uppercase text-[#8791A6]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->users() as $user)
                    <tr class="border-t border-[#E4DFD2]">
                        <td class="p-3 text-sm">{{ $user->name }}</td>
                        <td class="p-3 text-sm">{{ $user->email }}</td>
                        <td class="p-3">
                            <select wire:change="changeRole({{ $user->id }}, $event.target.value)" class="border border-[#E4DFD2] rounded text-sm p-1">
                                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="instructor" {{ $user->role === 'instructor' ? 'selected' : '' }}>Instructor</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </td>
                        <td class="p-3">
                            <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Yakin mau hapus user ini? Semua data terkait (course/enrollment) ikut terhapus." class="text-red-600 text-sm">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>{{ $this->users()->links() }}</div>
</div>