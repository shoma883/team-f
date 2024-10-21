<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('履歴') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          @foreach ($histories as $history)
            <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
              <p class="text-gray-800 dark:text-gray-300">{{ $history->name }}</p>
              <button onclick="showModal({{ $history->id }})" class="text-blue-500 hover:text-blue-700">
                詳細を見る
              </button>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <!-- モーダル -->
  <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-1/2 p-6">
      <div id="modal-content" class="text-gray-800 dark:text-gray-300">
        <!-- モーダルのコンテンツ挿入箇所 -->
      </div>
      <button onclick="closeModal()" class="mt-4 p-2 bg-blue-500 text-white rounded">
        閉じる
      </button>
    </div>
  </div>

</x-app-layout>


<script>
  function showModal(historyId) {
    // // Ajaxリクエストで詳細データを取得
    fetch(`/histories/${historyId}`)
      .then(response => response.json())
      .then(data => {
        // モーダルの内容を更新
        const modalContent = document.getElementById('modal-content');
        modalContent.innerHTML = `
          <h3 class="text-xl">${data.name}</h3>
          <ul>
            ${data.ingredients.map(ingredient => `
              <li>${ingredient.材料名}: ${ingredient.個数}</li>
            `).join('')}
          </ul>
        `;
        // モーダルを表示
        document.getElementById('modal').classList.remove('hidden');
      })
      .catch(error => console.error('Error fetching history details:', error));
  }

  function closeModal() {
    // モーダルを非表示
    document.getElementById('modal').classList.add('hidden');
  }
</script>
