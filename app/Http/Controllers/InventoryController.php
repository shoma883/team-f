<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function input(Request $request)
    {
        
        if ($request->isMethod('post')) {
        // POSTリクエストの場合、食材を追加
        $request->validate([
            'tweet' => 'required|string|max:255',
        ]);

        // 新しい食材をデータベースに保存
        $inventory = new Inventory();
        $inventory->inventory = $request->input('tweet');
        $inventory->user_id = $request->user()->id;
        $inventory->save();

        // 保存した食材をJSONで返す
        return response()->json(['inventory' => $inventory->inventory]);
    }

    // GETリクエストの場合、食材一覧を返す
    $inventories = Inventory::all();
    return view('inventories.input', [
        'inventories' => $inventories
    ]);
    }

    public function index()
    {
        //
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
        //
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
