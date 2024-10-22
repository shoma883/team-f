<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('在庫入力フォーム') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h3 class="text-lg font-semibold">料理名: {{ $selectedRecipe['料理名'] }}</h3> <!-- 料理名を表示 -->
          <form action="{{ route('gemini.update') }}" method="POST">
            @csrf
            <ul>
              @foreach ($selectedRecipe['材料'] as $ingredient)
                <li>
                  {{ $ingredient['材料名'] }}:
                  <span>初期数
                    <input type="number" name="initial_count[{{ $ingredient['材料名'] }}]"
                      value="{{ $inventories->firstWhere('name', $ingredient['材料名']) ? $inventories->firstWhere('name', $ingredient['材料名'])->stock : 0 }}"
                      readonly />
                  </span>
                  →
                  <span>変更後数
                    <input type="number" name="updated_count[{{ $ingredient['材料名'] }}]"
                      value="{{ ($inventoryItem = $inventories->firstWhere('name', $ingredient['材料名']))
                          ? $inventoryItem->stock - $ingredient['個数']
                          : -$ingredient['個数'] }}" />
                  </span>


                </li>
              @endforeach
            </ul>
            <button type="submit" class="mt-4 bg-blue-500 text-white font-bold py-2 px-4 rounded">入力</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
