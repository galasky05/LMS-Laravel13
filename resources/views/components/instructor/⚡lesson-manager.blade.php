<?php

use App\Models\Course;
use App\Models\Lesson;
use Livewire\Component;

new class extends Component {
    public Course $course;
    public $title = '';
    public $content = '';
    public $video_url = '';
    public $order = 0;
    public $editingId = null;

    public function mount($courseId)
    {
        $this->course = Course::where('id', $courseId)
            ->where('instructor_id', auth()->id())
            ->firstOrFail();
    }

    public function lessons()
    {
        return $this->course->lessons;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'content' => 'nullable',
            'video_url' => 'nullable|url',
            'order' => 'integer|min:0',
        ]);

        if ($this->editingId) {
            Lesson::where('id', $this->editingId)
                ->where('course_id', $this->course->id)
                ->update([
                    'title' => $this->title,
                    'content' => $this->content,
                    'video_url' => $this->video_url,
                    'order' => $this->order,
                ]);
        } else {
            Lesson::create([
                'course_id' => $this->course->id,
                'title' => $this->title,
                'content' => $this->content,
                'video_url' => $this->video_url,
                'order' => $this->order,
            ]);
        }

        $this->reset(['title', 'content', 'video_url', 'order', 'editingId']);
    }

    public function edit($id)
    {
        $lesson = Lesson::where('id', $id)->where('course_id', $this->course->id)->firstOrFail();
        $this->editingId = $lesson->id;
        $this->title = $lesson->title;
        $this->content = $lesson->content;
        $this->video_url = $lesson->video_url;
        $this->order = $lesson->order;
    }

    public function delete($id)
    {
        Lesson::where('id', $id)->where('course_id', $this->course->id)->delete();
    }
}
?>

<div class="p-6 space-y-6">
    <a href="{{ route('instructor.courses') }}" class="text-sm text-blue-600">&larr; Kembali ke daftar course</a>

    <h2 class="text-xl font-bold">Lesson untuk: {{ $course->title }}</h2>

    <form wire:submit="save" class="space-y-4 bg-white p-4 rounded shadow">
        <div>
            <label class="block text-sm font-medium">Judul Lesson</label>
            <input type="text" wire:model="title" class="border rounded w-full p-2">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Isi Materi (teks)</label>
            <textarea wire:model="content" rows="4" class="border rounded w-full p-2"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Link Video YouTube (opsional)</label>
            <input type="text" wire:model="video_url" placeholder="https://youtube.com/watch?v=..." class="border rounded w-full p-2">
            @error('video_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Urutan</label>
            <input type="number" wire:model="order" class="border rounded w-full p-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ $editingId ? 'Update Lesson' : 'Tambah Lesson' }}
        </button>
    </form>

    <div>
        <h2 class="text-xl font-bold mb-2">Daftar Lesson</h2>
        <table class="w-full bg-white rounded shadow">
            <thead>
                <tr class="border-b">
                    <th class="text-left p-2">Urutan</th>
                    <th class="text-left p-2">Judul</th>
                    <th class="text-left p-2">Video?</th>
                    <th class="text-left p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->lessons() as $lesson)
                    <tr class="border-b">
                        <td class="p-2">{{ $lesson->order }}</td>
                        <td class="p-2">{{ $lesson->title }}</td>
                        <td class="p-2">{{ $lesson->video_url ? 'Ya' : '-' }}</td>
                        <td class="p-2 space-x-2">
                            <button wire:click="edit({{ $lesson->id }})" class="text-blue-600">Edit</button>
                            <button wire:click="delete({{ $lesson->id }})" wire:confirm="Yakin mau hapus lesson ini?" class="text-red-600">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>