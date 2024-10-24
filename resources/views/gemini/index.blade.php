<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('提案') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 m-5">
          <form action="{{ route('gemini.entry') }}" method="post">
            @csrf
            <input name="toGeminiText" class="w-1/2 text-2xl" autofocus>
            @isset($result['task'])
              {{ $result['task'] }}
            @endisset
            </input>
            <button type="submit"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-2xl py-2 px-4 rounded focus:outline-none focus:shadow-outline">
              送信
            </button>
            @error('toGeminiText')
              <p class="text-red-500 italic">{{ $message }}</p>
            @enderror
          </form>

          @isset($dishes)
            @if (!empty($dishes['料理']))
              <form action="{{ route('gemini.save') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 py-10">
                  @foreach ($dishes['料理'] as $index => $dish)
                    <div class="card cursor-pointer p-4 border rounded shadow-md"
                      onclick="selectDish({{ $index }})" data-index="{{ $index }}">
                      <input type="hidden" name="recipes[{{ $index }}]" value="{{ json_encode($dish) }}">
                      <h3 class="text-2xl font-bold">{{ $dish['料理名'] }}</h3>
                      <ul>
                        @foreach ($dish['材料'] as $ingredient)
                          <li class="text-lg">{{ $ingredient['材料名'] }}: {{ $ingredient['個数'] }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endforeach
                </div>

                <input type="hidden" name="selected_recipe" id="selected_recipe">
                <button type="submit" class="py-2 px-4 bg-blue-500 font-bold text-2xl text-white rounded">保存</button>
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
    background-color: rgba(255, 255, 255, 0.1);
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
