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
          <h3 class="text-2xl font-bold">新規登録</h3>
          <form id="ingredient-form" method="POST" action="{{ route('inventories.index') }}" class="flex">
            @csrf
            <div class="flex">
              <div class="m-4">
                <label for="name" class="block text-gray-700 dark:text-gray-300 text-lg font-bold">食材名</label>
                <input type="text" name="name" id="name" class="rounded">
              </div>
              <div class="m-4">
                <label for="stock" class="block text-gray-700 dark:text-gray-300 text-lg font-bold">在庫数</label>
                <input type="number" id="stock" name="stock" class="rounded">
              </div>
            </div>
            <div class="flex items-end m-4">
              <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                登録
              </button>
            </div>
          </form>
          @error('name')
            <span class="text-red-500 text-xs italic">{{ $message }}</span>
          @enderror
          @error('stock')
            <span class="text-red-500 text-xs italic">{{ $message }}</span>
          @enderror
        </div>
      </div>
    </div>
  </div>

  <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg lg:px-10">
        <div class="p-10 text-gray-900 dark:text-gray-100">
          @foreach ($inventories as $inventory)
            <div class="flex items-center my-3">
              <p class="w-1/2 text-lg font-semibold text-gray-800 dark:text-gray-300 mr-4">{{ $inventory->name }}</p>
              <div>
                <button class="bg-green-500 hover:bg-green-700 text-brack font-bold py-1 px-2 rounded"
                  onclick="changeStock({{ $inventory->id }}, 1)">＋</button>
                <input type="number" id="stock-{{ $inventory->id }}" name="stock[{{ $inventory->id }}]"
                  value="{{ $inventory->stock }}" required class="border rounded text-right px-3 py-3 w-20 mx-3"
                  readonly>
                <button class="bg-red-500 hover:bg-red-700 text-brack font-bold py-1 px-2 rounded"
                  onclick="changeStock({{ $inventory->id }}, -1)">－</button>
              </div>
              <button class="bg-blue-500 hover:bg-red-700 text-white font-bold py-1 px-3 ml-20 rounded"
                onclick="deleteInventory({{ $inventory->id }})">削除</button>
              <span id="status-{{ $inventory->id }}" class="ml-20"></span>
            </div>
            <hr class="opacity-30">
          @endforeach
          <div class="flex justify-end">
            <button id="update-all"
              class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">更新</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    const changes = {};

    // 更新ボタンの表示制御
    const updateButtonStatus = () => {
      const updateAllButton = document.getElementById('update-all');
      if ((Object.keys(changes)).length === 0) {
        updateAllButton.classList.add('hidden');
      } else {
        updateAllButton.classList.remove('hidden');
      }
    }
    updateButtonStatus();

    function changeStock(inventoryId, change) {
      const stockInput = document.getElementById(`stock-${inventoryId}`);
      let currentStock = parseInt(stockInput.value);
      currentStock += change;
      stockInput.value = Math.max(0, currentStock);

      // 変更を追跡
      changes[inventoryId] = currentStock;

      let statusLabel = document.getElementById(`status-${inventoryId}`);
      if (!statusLabel) {
        statusLabel = document.createElement('span');
        statusLabel.id = `status-${inventoryId}`;
        statusLabel.className = 'changed';
        stockInput.parentElement.appendChild(statusLabel);
      }
      statusLabel.textContent = '変更あり';

      updateButtonStatus();
    }

    $('#update-all').on('click', function() {
      if (Object.keys(changes).length === 0) {
        alert('変更がありません。');
        return;
      }

      $.ajax({
        type: 'PUT',
        url: '{{ route('inventory.updateAll') }}',
        data: {
          changes: changes,
          _token: '{{ csrf_token() }}',
        },
        success: function(response) {
          alert('在庫が更新されました');

          Object.keys(changes).forEach(id => {
            let stockInput = document.getElementById(`stock-${id}`);
            let statusLabel = document.getElementById(`status-${id}`);

            if (!statusLabel) {
              statusLabel = document.createElement('span');
              statusLabel.id = `status-${id}`;
              statusLabel.className = 'text-green-500 ml-2';
              stockInput.parentElement.appendChild(statusLabel);
            }

            statusLabel.textContent = '変更済み';
          });

          Object.keys(changes).forEach(id => delete changes[id]); // 更新後に変更リストをクリア
          location.reload();
        },
        error: function(xhr) {
          alert('エラーが発生しました: ' + (xhr.responseJSON.message || '不明なエラー'));
        }
      });
    });

    function deleteInventory(inventoryId) {
      if (!confirm('本当に削除しますか？')) return;

      $.ajax({
        type: 'DELETE',
        url: '/inventory/' + inventoryId + '/delete',
        data: {
          _token: '{{ csrf_token() }}',
        },
        success: function(response) {
          alert('在庫が削除されました');
          $('#inventory-' + inventoryId).remove();
        },
        error: function(xhr) {
          alert('エラーが発生しました: ' + xhr.responseJSON.message);
        }
      });
    }
  </script>



</x-app-layout>
