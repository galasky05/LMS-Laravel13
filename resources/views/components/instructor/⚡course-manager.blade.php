<?php

use App\Models\Course;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component {
    public $title = '';
    public $description = '';
    public $price = 0;
    public $editingId = null;

    public function courses()
    {
        return Course::where('instructor_id', auth()->id())->latest()->get();
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'description' => 'nullable',
            'price' => 'numeric|min:0',
        ]);

        if ($this->editingId) {
            $course = Course::findOrFail($this->editingId);
            $course->update([
                'title' => $this->title,
                'description' => $this->description,
                'price' => $this->price,
            ]);
        } else {
            Course::create([
                'instructor_id' => auth()->id(),
                'title' => $this->title,
                'slug' => Str::slug($this->title) . '-' . uniqid(),
                'description' => $this->description,
                'price' => $this->price,
            ]);
        }

        $this->reset(['title', 'description', 'price', 'editingId']);
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $this->editingId = $course->id;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->price = $course->price;
    }

    public function delete($id)
    {
        Course::where('id', $id)->where('instructor_id', auth()->id())->delete();
    }

    public function togglePublish($id)
    {
        $course = Course::where('id', $id)->where('instructor_id', auth()->id())->first();
        $course->update(['is_published' => !$course->is_published]);
    }
}
?>

<div class="p-6 space-y-6">
    <h2 class="text-xl font-bold">{{ $editingId ? 'Edit Course' : 'Tambah Course Baru' }}</h2>

    <form wire:submit="save" class="space-y-4 bg-white p-4 rounded shadow">
        <div>
            <label class="block text-sm font-medium">Judul Course</label>
            <input type="text" wire:model="title" class="border rounded w-full p-2">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Deskripsi</label>
            <textarea wire:model="description" class="border rounded w-full p-2"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Harga (Rp)</label>
            <input type="number" wire:model="price" class="border rounded w-full p-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ $editingId ? 'Update' : 'Simpan' }}
        </button>
    </form>

    <div>
        <h2 class="text-xl font-bold mb-2">Daftar Course Kamu</h2>
        <table class="w-full bg-white rounded shadow">
            <thead>
                <tr class="border-b">
                    <th class="text-left p-2">Judul</th>
                    <th class="text-left p-2">Harga</th>
                    <th class="text-left p-2">Status</th>
                    <th class="text-left p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->courses() as $course)
                    <tr class="border-b">
                        <td class="p-2">{{ $course->title }}</td>
                        <td class="p-2">Rp{{ number_format($course->price) }}</td>
                        <td class="p-2">
                            <button wire:click="togglePublish({{ $course->id }})"
                                class="text-xs px-2 py-1 rounded {{ $course->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $course->is_published ? 'Published' : 'Draft' }}
                            </button>
                        </td>
                        <td class="p-2 space-x-2">
                            <a href="{{ route('instructor.lessons', $course->id) }}" class="text-purple-600">Kelola Lesson</a>
                            <button wire:click="edit({{ $course->id }})" class="text-blue-600">Edit</button>
                            <button wire:click="delete({{ $course->id }})" wire:confirm="Yakin mau hapus course ini?" class="text-red-600">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>