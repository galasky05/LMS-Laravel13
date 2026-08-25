<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Kelola Soal</h2></x-slot>
    <livewire:instructor.question-manager :quiz-id="$quizId" />
</x-app-layout>