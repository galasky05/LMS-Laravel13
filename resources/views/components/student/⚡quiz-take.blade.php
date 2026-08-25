<?php

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Livewire\Component;

new class extends Component {
    public Quiz $quiz;
    public $answers = [];
    public $result = null;

    public function mount($quizId)
    {
        $this->quiz = Quiz::with('questions.options')->findOrFail($quizId);
    }

    public function submit()
    {
        $this->validate([
            'answers.*' => 'required',
        ]);

        $total = $this->quiz->questions->count();
        $correct = 0;

        foreach ($this->quiz->questions as $question) {
            $selectedOptionId = $this->answers[$question->id] ?? null;
            $correctOption = $question->options->firstWhere('is_correct', true);

            if ($selectedOptionId == $correctOption->id) {
                $correct++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100) : 0;
        $isPassed = $score >= $this->quiz->passing_score;

        QuizAttempt::create([
            'user_id' => auth()->id(),
            'quiz_id' => $this->quiz->id,
            'score' => $score,
            'is_passed' => $isPassed,
            'answers' => $this->answers,
            'completed_at' => now(),
        ]);

        $this->result = [
            'score' => $score,
            'correct' => $correct,
            'total' => $total,
            'is_passed' => $isPassed,
        ];
    }
}
?>

<div class="p-6">
    @if($result)
        <div class="bg-white p-6 rounded shadow text-center space-y-2">
            <h2 class="text-2xl font-bold">{{ $result['is_passed'] ? 'Selamat, Kamu Lulus! 🎉' : 'Belum Lulus 😔' }}</h2>
            <p class="text-lg">Skor: {{ $result['score'] }}% ({{ $result['correct'] }}/{{ $result['total'] }} benar)</p>
            <p class="text-sm text-gray-500">Skor minimal lulus: {{ $quiz->passing_score }}%</p>
        </div>
    @else
        <h2 class="text-xl font-bold mb-4">{{ $quiz->title }}</h2>
        <form wire:submit="submit" class="space-y-4">
            @foreach($quiz->questions as $i => $question)
                <div class="bg-white p-4 rounded shadow">
                    <p class="font-semibold mb-2">{{ $i + 1 }}. {{ $question->question_text }}</p>
                    @foreach($question->options as $option)
                        <label class="block">
                            <input type="radio" wire:model="answers.{{ $question->id }}" value="{{ $option->id }}">
                            {{ $option->option_text }}
                        </label>
                    @endforeach
                    @error("answers.{$question->id}") <span class="text-red-500 text-sm">Wajib dijawab</span> @enderror
                </div>
            @endforeach
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit Quiz</button>
        </form>
    @endif
</div>