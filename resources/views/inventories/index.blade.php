<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('在庫管理') }}
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
              <input type="text" name="name" id="name"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              <input type="number" id="stock" name="stock" required>
              @error('name')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
              @enderror
              @error('stock')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
              @enderror
            </div>
            <button type="submit"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">決定</button>
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
              <div class="flex items-center mb-2">
                <p class="text-gray-800 dark:text-gray-300 mr-4">{{ $inventory->name }}</p>
                <button class="bg-green-500 hover:bg-green-700 text-brack font-bold py-1 px-2 rounded"
                  onclick="changeStock({{ $inventory->id }}, 1)">＋</button>
                <input type="number" id="stock-{{ $inventory->id }}" name="stock[{{ $inventory->id }}]"
                  value="{{ $inventory->stock }}" required class="border rounded px-3 py-3 w-12 mx-3" readonly>
                <button class="bg-red-500 hover:bg-red-700 text-brack font-bold py-1 px-2 rounded"
                  onclick="changeStock({{ $inventory->id }}, -1)">－</button>
                <button class="ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                  onclick="updateStock({{ $inventory->id }})">更新</button>
                <button class="ml-4 bg-blue-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded"
                  onclick="deleteInventory({{ $inventory->id }})">削除</button>
              </div>
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

            $('#inventory-list').append('<p class="text-gray-800 dark:text-gray-300">' + response
              .inventory + '</p>');
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

    function changeStock(inventoryId, change) {
      const stockInput = document.getElementById(`stock-${inventoryId}`);
      let currentStock = parseInt(stockInput.value);
      currentStock += change; // 増減を加算
      stockInput.value = Math.max(0, currentStock); // 負の数にならないように
    }

    function updateStock(inventoryId) {
      const stockValue = document.getElementById(`stock-${inventoryId}`).value;

      $.ajax({
        type: 'PUT',
        url: '/inventory/' + inventoryId + '/update', // 適切な更新URLを設定
        data: {
          stock: stockValue,
          _token: '{{ csrf_token() }}', // CSRFトークンを送信
        },
        success: function(response) {
          alert('在庫が更新されました');
        },
        error: function(xhr) {
          alert('エラーが発生しました: ' + xhr.responseJSON.message);
        }
      });
    }

    function deleteInventory(inventoryId) {
      if (!confirm('本当に削除しますか？')) return;

      $.ajax({
        type: 'DELETE',
        url: '/inventory/' + inventoryId + '/delete', // 削除用URL
        data: {
          _token: '{{ csrf_token() }}', // CSRFトークンを送信
        },
        success: function(response) {
          alert('在庫が削除されました');
          $('#inventory-' + inventoryId).remove(); // 削除されたアイテムを画面からも消去
        },
        error: function(xhr) {
          alert('エラーが発生しました: ' + xhr.responseJSON.message);
        }
  });
}

  </script>

</x-app-layout>
