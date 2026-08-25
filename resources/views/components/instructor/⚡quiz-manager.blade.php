<?php

use App\Models\Course;
use App\Models\Quiz;
use Livewire\Component;

new class extends Component {
    public Course $course;
    public $title = '';
    public $description = '';
    public $passing_score = 70;
    public $editingId = null;

    public function mount($courseId)
    {
        $this->course = Course::where('id', $courseId)
            ->where('instructor_id', auth()->id())
            ->firstOrFail();
    }

    public function quizzes()
    {
        return $this->course->quizzes()->withCount('questions')->get();
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'passing_score' => 'integer|min:0|max:100',
        ]);

        if ($this->editingId) {
            Quiz::where('id', $this->editingId)->update([
                'title' => $this->title,
                'description' => $this->description,
                'passing_score' => $this->passing_score,
            ]);
        } else {
            Quiz::create([
                'course_id' => $this->course->id,
                'title' => $this->title,
                'description' => $this->description,
                'passing_score' => $this->passing_score,
            ]);
        }

        $this->reset(['title', 'description', 'editingId']);
        $this->passing_score = 70;
    }

    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        $this->editingId = $quiz->id;
        $this->title = $quiz->title;
        $this->description = $quiz->description;
        $this->passing_score = $quiz->passing_score;
    }

    public function delete($id)
    {
        Quiz::where('id', $id)->where('course_id', $this->course->id)->delete();
    }
}
?>

<div class="p-6 space-y-6">
    <a href="{{ route('instructor.courses') }}" class="text-sm text-blue-600">&larr; Kembali ke daftar course</a>

    <h2 class="text-xl font-bold">Quiz untuk: {{ $course->title }}</h2>

    <form wire:submit="save" class="space-y-4 bg-white p-4 rounded shadow">
        <div>
            <label class="block text-sm font-medium">Judul Quiz</label>
            <input type="text" wire:model="title" class="border rounded w-full p-2">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Deskripsi</label>
            <textarea wire:model="description" class="border rounded w-full p-2"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Skor Minimal Lulus (%)</label>
            <input type="number" wire:model="passing_score" class="border rounded w-full p-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ $editingId ? 'Update Quiz' : 'Buat Quiz' }}
        </button>
    </form>

    <div>
        <h2 class="text-xl font-bold mb-2">Daftar Quiz</h2>
        <table class="w-full bg-white rounded shadow">
            <thead>
                <tr class="border-b">
                    <th class="text-left p-2">Judul</th>
                    <th class="text-left p-2">Jumlah Soal</th>
                    <th class="text-left p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->quizzes() as $quiz)
                    <tr class="border-b">
                        <td class="p-2">{{ $quiz->title }}</td>
                        <td class="p-2">{{ $quiz->questions_count }} soal</td>
                        <td class="p-2 space-x-2">
                            <a href="{{ route('instructor.quiz.questions', $quiz->id) }}" class="text-purple-600">Kelola Soal</a>
                            <button wire:click="edit({{ $quiz->id }})" class="text-blue-600">Edit</button>
                            <button wire:click="delete({{ $quiz->id }})" wire:confirm="Yakin mau hapus quiz ini?" class="text-red-600">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>