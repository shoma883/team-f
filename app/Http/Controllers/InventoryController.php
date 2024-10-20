<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function input()
    {
        //
        
    }

    public function index(Request $request)
    {
       // POSTリクエストの場合
    if ($request->isMethod('post')) {
        // 食材のバリデーション
        $request->validate([
            'inventory' => 'required|string|max:255',
        ]);

        // 新しい食材をデータベースに保存
        $inventory = new Inventory();
        $inventory->name = $request->input('inventory');
        $inventory->save();

        // 保存した食材をJSONで返す
        return response()->json(['inventory' => $inventory->inventory]);
    }

    // GETリクエストの場合、食材一覧を返す
    $inventories = Inventory::all();
    return view('inventories.index', [ // ビュー名を `inventories.index` に変更
        'inventories' => $inventories
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::table('inventories')->insert([
            'name' => 'いも',
            'inventory' => 0, // または適切な値を指定
            'created_at' => now(),
            'updated_at' => now(),
        ]);
         return redirect()->back()->with('success', '在庫が追加されました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }

    
}
