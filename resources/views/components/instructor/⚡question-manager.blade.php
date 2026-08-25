<?php

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Services\GeminiQuizService;
use Livewire\Component;

new class extends Component {
    public Quiz $quiz;
    public $question_text = '';
    public $options = ['', '', '', ''];
    public $correctIndex = 0;
    public $editingId = null;

    public $aiCount = 5;
    public $isGenerating = false;
    public $aiError = null;

    public function mount($quizId)
    {
        $this->quiz = Quiz::whereHas('course', function ($q) {
            $q->where('instructor_id', auth()->id());
        })->findOrFail($quizId);
    }

    public function questions()
    {
        return $this->quiz->questions()->with('options')->orderBy('order')->get();
    }

    public function generateWithAI()
    {
        $this->aiError = null;
        $this->isGenerating = true;

        try {
            $materialText = $this->quiz->course->lessons
                ->pluck('content')
                ->filter()
                ->implode("\n\n");

            if (empty(trim($materialText))) {
                throw new \Exception('Course ini belum punya materi lesson (teks) yang bisa dipakai untuk generate soal.');
            }

            $service = new GeminiQuizService();
            $generated = $service->generateQuestions($materialText, $this->aiCount);

            $order = $this->quiz->questions()->count();

            foreach ($generated as $item) {
                $question = Question::create([
                    'quiz_id' => $this->quiz->id,
                    'question_text' => $item['question'],
                    'order' => $order++,
                ]);

                foreach ($item['options'] as $i => $optionText) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct' => $i == $item['correct_index'],
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->aiError = $e->getMessage();
        }

        $this->isGenerating = false;
    }

    public function save()
    {
        $this->validate([
            'question_text' => 'required|min:3',
            'options.*' => 'required|min:1',
        ]);

        if ($this->editingId) {
            $question = Question::findOrFail($this->editingId);
            $question->update(['question_text' => $this->question_text]);
            $question->options()->delete();
        } else {
            $question = Question::create([
                'quiz_id' => $this->quiz->id,
                'question_text' => $this->question_text,
                'order' => $this->quiz->questions()->count(),
            ]);
        }

        foreach ($this->options as $i => $text) {
            Option::create([
                'question_id' => $question->id,
                'option_text' => $text,
                'is_correct' => $i == $this->correctIndex,
            ]);
        }

        $this->reset(['question_text', 'editingId']);
        $this->options = ['', '', '', ''];
        $this->correctIndex = 0;
    }

    public function edit($id)
    {
        $question = Question::with('options')->findOrFail($id);
        $this->editingId = $question->id;
        $this->question_text = $question->question_text;
        $this->options = $question->options->pluck('option_text')->toArray();
        $this->correctIndex = $question->options->search(fn($o) => $o->is_correct);
    }

    public function delete($id)
    {
        Question::where('id', $id)->where('quiz_id', $this->quiz->id)->delete();
    }
}
?>

<div class="p-6 space-y-6">
    <a href="{{ route('instructor.courses.quizzes', $quiz->course_id) }}" class="text-sm text-blue-600">&larr; Kembali ke daftar quiz</a>

    <h2 class="text-xl font-bold">Soal untuk: {{ $quiz->title }}</h2>

    <div class="bg-purple-50 border border-purple-200 p-4 rounded space-y-3">
    <h3 class="font-semibold text-purple-800">✨ Generate Soal Otomatis dengan AI</h3>
    <p class="text-sm text-gray-600">Soal akan dibuat otomatis berdasarkan materi (teks) lesson yang ada di course ini.</p>

    <div class="flex items-center gap-2">
        <label class="text-sm">Jumlah soal:</label>
        <input type="number" wire:model="aiCount" min="1" max="10" class="border rounded p-1 w-20">
        <button wire:click="generateWithAI" wire:loading.attr="disabled" class="bg-purple-600 text-white px-4 py-2 rounded">
            <span wire:loading.remove wire:target="generateWithAI">Generate Soal</span>
            <span wire:loading wire:target="generateWithAI">Sedang membuat soal...</span>
        </button>
    </div>

    @if($aiError)
        <p class="text-red-600 text-sm">{{ $aiError }}</p>
    @endif
</div>

    <form wire:submit="save" class="space-y-4 bg-white p-4 rounded shadow">
        <div>
            <label class="block text-sm font-medium">Pertanyaan</label>
            <textarea wire:model="question_text" class="border rounded w-full p-2"></textarea>
            @error('question_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium">Pilihan Jawaban (pilih radio buat jawaban benar)</label>
            @foreach($options as $i => $option)
                <div class="flex items-center gap-2">
                    <input type="radio" name="correct_option" wire:model="correctIndex" value="{{ $i }}">
                    <input type="text" wire:model="options.{{ $i }}" placeholder="Opsi {{ $i + 1 }}" class="border rounded w-full p-2">
                </div>
                @error("options.$i") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @endforeach
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ $editingId ? 'Update Soal' : 'Tambah Soal' }}
        </button>
    </form>

    <div>
        <h2 class="text-xl font-bold mb-2">Daftar Soal</h2>
        <div class="space-y-3">
            @foreach($this->questions() as $question)
                <div class="bg-white p-4 rounded shadow">
                    <div class="flex justify-between">
                        <p class="font-semibold">{{ $question->question_text }}</p>
                        <div class="space-x-2">
                            <button wire:click="edit({{ $question->id }})" class="text-blue-600 text-sm">Edit</button>
                            <button wire:click="delete({{ $question->id }})" wire:confirm="Yakin mau hapus soal ini?" class="text-red-600 text-sm">Hapus</button>
                        </div>
                    </div>
                    <ul class="mt-2 text-sm">
                        @foreach($question->options as $option)
                            <li class="{{ $option->is_correct ? 'text-green-600 font-semibold' : 'text-gray-600' }}">
                                {{ $option->is_correct ? '✅' : '⬜' }} {{ $option->option_text }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>