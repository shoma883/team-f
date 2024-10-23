@php
  $mockDishes = [
      '料理' => [
          [
              '料理名' => 'カレーライス',
              '材料' => [
                  ['材料名' => 'じゃがいも', '個数' => '2個'],
                  ['材料名' => 'にんじん', '個数' => '1本'],
                  ['材料名' => '玉ねぎ', '個数' => '1個'],
                  ['材料名' => 'カレールー', '個数' => '1箱'],
                  ['材料名' => 'ご飯', '個数' => '適量'],
              ],
          ],
          [
              '料理名' => 'スパゲッティ',
              '材料' => [
                  ['材料名' => 'スパゲッティ', '個数' => '200g'],
                  ['材料名' => 'トマトソース', '個数' => '1缶'],
                  ['材料名' => 'にんにく', '個数' => '1片'],
                  ['材料名' => 'オリーブオイル', '個数' => '大さじ2'],
                  ['材料名' => '塩', '個数' => '適量'],
              ],
          ],
          [
              '料理名' => 'サラダ',
              '材料' => [
                  ['材料名' => 'レタス', '個数' => '1/2個'],
                  ['材料名' => 'トマト', '個数' => '1個'],
                  ['材料名' => 'きゅうり', '個数' => '1本'],
                  ['材料名' => 'ドレッシング', '個数' => '適量'],
              ],
          ],
          [
              '料理名' => 'オムライス',
              '材料' => [
                  ['材料名' => 'ご飯', '個数' => '適量'],
                  ['材料名' => '玉ねぎ', '個数' => '1個'],
                  ['材料名' => '鶏肉', '個数' => '100g'],
                  ['材料名' => 'ケチャップ', '個数' => '大さじ2'],
                  ['材料名' => '卵', '個数' => '2個'],
              ],
          ],
          [
              '料理名' => '親子丼',
              '材料' => [
                  ['材料名' => 'ご飯', '個数' => '適量'],
                  ['材料名' => '鶏肉', '個数' => '100g'],
                  ['材料名' => '玉ねぎ', '個数' => '1個'],
                  ['材料名' => '卵', '個数' => '2個'],
                  ['材料名' => 'しょうゆ', '個数' => '大さじ2'],
              ],
          ],
      ],
  ];
@endphp

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
          </form>

          <form action="{{ route('gemini.save') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 py-10">
              @foreach ($mockDishes['料理'] as $index => $dish)
                <div class="card cursor-pointer p-4 border rounded shadow-md" onclick="selectDish({{ $index }})"
                  data-index="{{ $index }}">
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

          @isset($dishes)
            @if (!empty($dishes['料理']))
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
