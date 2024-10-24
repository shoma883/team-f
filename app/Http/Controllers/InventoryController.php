<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function input()
    {
        // インプット用の処理
    }

    public function index(Request $request)
    {
        // GETリクエストの場合、食材一覧を返す
        $inventories = Inventory::all();
        return view('inventories.index', [
            'inventories' => $inventories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 作成用の処理
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer',
        ]);

        // 食材が既に存在するかを確認
        $existingInventory = Inventory::where('name', $request->input('name'))->first();

        if ($existingInventory) {
            // 既存の食材がある場合は在庫を追加する
            $existingInventory->stock += $request->input('stock');
            $existingInventory->save();

            return redirect()->back()->with('success', '在庫が更新されました。');
        } else {
            // 新しい食材をデータベースに保存
            Inventory::create([
                'name' => $request->input('name'),
                'stock' => $request->input('stock'),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()->with('success', '新しい食材が追加されました。');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        // 表示用の処理
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        // 編集用の処理
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->stock = $request->input('stock');
        $inventory->save();

        return response()->json(['success' => '在庫が更新されました。']);
        // 更新用の処理
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // 削除用の処理
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return response()->json(['message' => '在庫が削除されました']);
    }
}
