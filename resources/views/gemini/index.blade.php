


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

                @isset($dishes)

                    @if (!empty($dishes['料理']))
                        <ul>
                            @foreach ($dishes['料理'] as $dish)
                                <li>
                                <!-- <a href="{{ route('inventory.show', ['dish' => $dish['料理名']]) }}" 
                                   onclick="return confirm('この料理の在庫フォームに移動しますか？')"> -->
                                    <strong>{{ $dish['料理名'] }}</strong>
                                    <ul>
                                        @foreach ($dish['材料'] as $ingredient)
                                            <li>{{ $ingredient['材料名'] }}: {{ $ingredient['個数'] }}</li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>料理のデータが見つかりませんでした。</p>
                    @endif
                @endisset


                <!-- @isset($result)
                <p>{!!$result['content']!!}</p>
                @endisset -->
                </div>
            </div>
=======
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <form action="{{ route('gemini.entry') }}" method="post">
            @csrf
            <textarea name="toGeminiText" autofocus>
@isset($result['task'])
{{ $result['task'] }}
@endisset
</textarea>
            <button type="submit">send</button>
          </form>

          <hr>

          @isset($result)
            <p>{!! $result['content'] !!}</p>
          @endisset

        </div>
      </div>
    </div>
  </div>
</x-app-layout>
