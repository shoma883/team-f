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
        //
        
    }

    public function index(Request $request)
    {
       // POSTリクエストの場合
    if ($request->isMethod('post')) {
        // 食材のバリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer',
        ]);

        // 新しい食材をデータベースに保存
        $inventory = new Inventory();
        $inventory->name = $request->input('name');
        $inventory->stock = $request->input('stock');
        $inventory->user_id = auth()->id();
        $inventory->save();

        // 保存した食材をJSONで返す
        return response()->json(['inventory' => $inventory->inventory]);
    }

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255|unique:inventories,name',
            'stock' => 'required|integer', 
             ], [
        'name.unique' => 'この食材はすでに存在します。' // エラーメッセージのカスタマイズ
 
        ]);

        DB::table('inventories')->insert([
            'name' => $request->input('name'), 
            'stock' => $request->input('stock'), 
            'user_id' => auth()->id(), 
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
        $request->validate([
        'stock' => 'required|integer',
    ]);

    
      $inventory->stock = $request->input('stock');
      $inventory->save();

    return response()->json(['success' => '在庫が更新されました。']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }

    
}
