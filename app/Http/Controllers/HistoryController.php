<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $histories = History::where('user_id', auth()->id())->get();
    return view('histories.index', compact('histories'));
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
  public function show($id)
  {
    $history = \App\Models\History::findOrFail($id);

    // ingredientsをJSONから配列にデコード
    $history->ingredients = json_decode($history->ingredients, true);

    return response()->json($history);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
