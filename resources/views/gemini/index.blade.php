<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <form action="{{route('entry')}}" method="post">
                    @csrf
                    <textarea name="toGeminiText" autofocus>@isset($result['task']){{$result['task']}}@endisset </textarea>
                    <button type="submit">send</button>
                </form>
                
                <hr>

                @isset($result)
                <p>{!!$result['content']!!}</p>
                @endisset
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
