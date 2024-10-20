<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('食材入力フォーム') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <form id="ingredient-form" method="POST" action="{{ route('inventories.index') }}">
            @csrf
            <div class="mb-4">
              <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">食材</label>
              <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              <input type="number" id="stock" name="stock" required>
              @error('name')
              <span class="text-red-500 text-xs italic">{{ $message }}</span>
              @enderror
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">決定</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg" id="inventory-list">
           @foreach ($inventories as $inventory)
           <p class="text-gray-800 dark:text-gray-300">{{ $inventory->name }} (在庫: {{ $inventory->stock }})</p>
           @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#ingredient-form').on('submit', function(e) {
        e.preventDefault(); 

        $.ajax({
          type: 'POST',
          url: $(this).attr('action'),
          data: $(this).serialize(), 
          success: function(response) {
            $('#inventory-list').append('<p class="text-gray-800 dark:text-gray-300">' + response.inventory.name + ' (在庫: ' + response.inventory.stock + ')</p>');
            $('#name').val(''); 
            $('#stock').val('');
          },
          error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.message) {
              alert('エラーが発生しました: ' + xhr.responseJSON.message);
            } else {
              alert('エラーが発生しました: 不明なエラー');
            }
          }
        });
      });
    });
  </script>

</x-app-layout>
