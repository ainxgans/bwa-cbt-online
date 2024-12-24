<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden sm:rounded-lg">
                <a href="{{route('dashboard.courses.create')}}"
                   class="py-4 px-5 rounded-full bg-indigo-600 text-white">
                    Create new course
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
