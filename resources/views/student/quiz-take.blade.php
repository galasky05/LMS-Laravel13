<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Kerjakan Quiz</h2></x-slot>
    <livewire:student.quiz-take :quiz-id="$quizId" />
</x-app-layout>