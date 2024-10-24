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

          <!-- フォーム -->
          <form id="geminiForm" action="{{ route('gemini.entry') }}" method="POST">
            @csrf
            <input name="toGeminiText" class="w-1/2 text-2xl" autofocus>
            <button type="submit"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold text-2xl py-2 px-4 rounded focus:outline-none focus:shadow-outline">
              送信
            </button>
            @error('toGeminiText')
              <p class="text-red-500 italic">{{ $message }}</p>
            @enderror
          </form>

          <!-- プログレスバー -->
          <div id="progress-bar" class="my-10 bg-gray-200 h-4 rounded-full">
            <div class="bg-blue-500 h-2 rounded-full" style="width: 0; height: 10px;"></div>
          </div>

          <!-- レスポンスの表示領域 -->
          <div id="response-content" class="mt-5">
            <!-- ここにレスポンスが表示されます -->
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
  </div>
</x-app-layout>

<script>
  // フォーム送信時にプログレスバーを表示
  document.getElementById('geminiForm').addEventListener('submit', function(event) {
    event.preventDefault(); // デフォルトのフォーム送信を一旦停止
    const form = event.target;

    // プログレスバーを表示
    const progressBar = document.getElementById('progress-bar');
    progressBar.classList.remove('hidden');

    // プログレスバーの進行アニメーション
    const progress = progressBar.querySelector('div');
    progress.style.width = '0%';
    let width = 0;
    const interval = setInterval(function() {
      if (width >= 100) {
        clearInterval(interval);
      } else {
        width += 0.5;
        progress.style.width = width + '%';
      }
    }, 35);

    // フォームを送信
    form.submit();
  });

  // ダークモード判定
  function isDarkMode() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  function selectDish(index) {
    document.getElementById('selected_recipe').value = index;
    // クリックしたカードをハイライト表示
    document.querySelectorAll('.card').forEach(card => {
      card.classList.remove('bg-custom-highlight-dark', 'bg-custom-highlight-white');
    });

    if (isDarkMode()) {
      document.querySelector(`[data-index="${index}"]`).classList.add('bg-custom-highlight-dark');
    } else {
      document.querySelector(`[data-index="${index}"]`).classList.add('bg-custom-highlight-white');
    }
  }
</script>

<style>
  .bg-custom-highlight-white {
    background-color: rgba(0, 0, 0, 0.1);
  }

  .bg-custom-highlight-dark {
    background-color: rgba(255, 255, 255, 0.1);
  }
</style>
