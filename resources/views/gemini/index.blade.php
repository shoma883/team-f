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
          <form action="{{ route('gemini.entry') }}" method="post">
            @csrf
            <input name="toGeminiText" autofocus>
            @isset($result['task'])
              {{ $result['task'] }}
            @endisset
            </input>
            <button type="submit">send</button>
          </form>

          <hr>

          @isset($dishes)

            @if (!empty($dishes['料理']))
              {{-- <ul>
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
              </ul> --}}
              <form action="{{ route('gemini.save') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                  @foreach ($dishes['料理'] as $index => $dish)
                    <div class="card cursor-pointer p-4 border rounded shadow-md"
                      onclick="selectDish({{ $index }})" data-index="{{ $index }}">
                      <input type="hidden" name="recipes[{{ $index }}]" value="{{ json_encode($dish) }}">
                      <strong>{{ $dish['料理名'] }}</strong>
                      <ul>
                        @foreach ($dish['材料'] as $ingredient)
                          <li>{{ $ingredient['材料名'] }}: {{ $ingredient['個数'] }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endforeach
                </div>

                <input type="hidden" name="selected_recipe" id="selected_recipe">
                <button type="submit" class="mt-4 p-2 bg-blue-500 text-white rounded">選択した料理を保存する</button>
              </form>
            @else
              <p>料理のデータが見つかりませんでした。</p>
            @endif
          @endisset
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

<style>
  .bg-custom-highlight {
    background-color: rgba(255, 0, 0);
  }
</style>

<script>
  function selectDish(index) {
    document.getElementById('selected_recipe').value = index;
    console.log(index);
    // クリックしたカードをハイライト表示
    document.querySelectorAll('.card').forEach(card => {
      card.classList.remove('bg-custom-highlight');
    });
    document.querySelector(`[data-index="${index}"]`).classList.add('bg-custom-highlight');
    console.log(document.querySelector(`[data-index="${index}"]`).classList);
  }
</script>
