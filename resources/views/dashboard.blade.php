<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Επισκέψεις') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @livewire('visits')
    </div>
</x-app-layout>