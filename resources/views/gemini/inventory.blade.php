<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('在庫変更') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-3xl font-bold">{{ $selectedRecipe['料理名'] }}</h3> <!-- 料理名を表示 -->
          <form action="{{ route('gemini.update') }}" method="POST">
            @csrf
            <ul class="mt-5">
              @foreach ($selectedRecipe['材料'] as $ingredient)
                <li class="flex items-center w-2/3 m-2">
                  <h4 class="text-xl font-semibold w-1/3">{{ $ingredient['材料名'] }}</h4>
                  <input name="initial_count[{{ $ingredient['材料名'] }}]"
                    class="w-1/12 text-xl text-center border rounded p-1"
                    value="{{ $inventories->firstWhere('name', $ingredient['材料名'])
                        ? $inventories->firstWhere('name', $ingredient['材料名'])->stock
                        : 0 }}"
                    readonly />
                  <span class="mx-5">&#x279C;</span>
                  <input name="updated_count[{{ $ingredient['材料名'] }}]"
                    class="w-1/12 text-xl text-center border rounded p-1"
                    value="{{ ($inventoryItem = $inventories->firstWhere('name', $ingredient['材料名']))
                        ? $inventoryItem->stock - $ingredient['個数']
                        : -$ingredient['個数'] }}"
                    readonly />
                  <!-- 変更後数がマイナスの場合に「不足しています」を表示 -->
                  @if (($inventoryItem ? $inventoryItem->stock - $ingredient['個数'] : -$ingredient['個数']) < 0)
                    <p class="text-red-500 ml-5">材料が不足しています</p>
                  @endif
                </li>
              @endforeach
            </ul>
            <div class="flex justify-end">
              <button type="submit"
                class="w-24 mt-4 bg-blue-500 text-white font-bold py-2 px-4 rounded tracking-widest hover:bg-blue-700 focus:outline-none focus:shadow-outline"
                onclick="return confirm('この内容で食材を追加してもよろしいですか？')">登録</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
