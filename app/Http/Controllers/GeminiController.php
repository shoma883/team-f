<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory; // Inventoryモデルをインポート
use Gemini\Laravel\Facades\Gemini;

use Illuminate\Support\Str;

class GeminiController extends Controller
{
  public function index()
  {
    return view('gemini.index');
  }

  public function entry(Request $request)
  {
    $request->validate([
      'toGeminiText' => 'required|string',
    ]);

    $searchQuery = $request->toGeminiText;

    $toGeminiCommand = "'{$searchQuery}'に基づいて、料理の名前と必要な材料(必ずひらがなで出力)と材料の個数をJSON形式で5つ分出力してください。材料名は必ず**ひらがな**で出力し、絶対にカタカナや漢字は含まないでください。例：「にんじん」、「たまねぎ」、「にく」、「はむ」、「ぴーまん」など。すべての数量は**整数のみ**で、調味料は含めないでください。単位は表示しないでください。以下の形式で返答してください。以下に示すのは一例です。
      {
        \"料理\": [
          {
            \"料理名\": \"カレー\",
            \"材料\": [
              { \"材料名\": \"にんじん\", \"個数\": 2 },
              { \"材料名\": \"じゃがいも\", \"個数\": 3 },
              { \"材料名\": \"たまねぎ\", \"個数\": 1 },
              { \"材料名\": \"にく\", \"個数\": 300 }
            ]
          },
          {
            \"料理名\": \"サラダ\",
            \"材料\": [
              { \"材料名\": \"きゅうり\", \"個数\": 1 },
              { \"材料名\": \"とまと\", \"個数\": 2 },
              { \"材料名\": \"れたす\", \"個数\": 1 }
            ]
          },
          {
            \"料理名\": \"スパゲッティ\",
            \"材料\": [
              { \"材料名\": \"ぱすた\", \"個数\": 200 },
              { \"材料名\": \"とまと\", \"個数\": 1 },
              { \"材料名\": \"にんにく\", \"個数\": 1 }
            ]
          },
          {
            \"料理名\": \"お好み焼き\",
            \"材料\": [
              { \"材料名\": \"きゃべつ\", \"個数\": 1 },
              { \"材料名\": \"もやし\", \"個数\": 100 },
              { \"材料名\": \"ぶたにく\", \"個数\": 150 }
            ]
          },
          {
            \"料理名\": \"みそ汁\",
            \"材料\": [
              { \"材料名\": \"とうふ\", \"個数\": 1 },
              { \"材料名\": \"わかめ\", \"個数\": 10 },
              { \"材料名\": \"ねぎ\", \"個数\": 2 }
            ]
            }
        ]
      }";

    //GeminiAIからのレスポンスを取得
    $responseText = Gemini::geminiPro()->generateContent($toGeminiCommand)->text();

    $result = [
      'task' => $searchQuery,
      'content' => ($responseText),
    ];

    // バッククォートを取り除く
    $cleanedContent = str_replace(['```', '```'], '', $result['content']);

    // 不要な部分を削除
    $cleanedContent = str_replace('json', '', $cleanedContent);

    $cleanedContent = str_replace('JSON', '', $cleanedContent);
    //dd($cleanedContent);


    // 配列にデコード
    $dishes = json_decode($cleanedContent, true);


    // デコード結果を確認
    if (json_last_error() !== JSON_ERROR_NONE) {
      dd('JSONエラー: ' . json_last_error_msg(), $cleanedContent);
    } else {
      //dd($cleanedContent);
    }

    // ビューに結果を渡して表示
    return view('gemini.index', compact('dishes'));

    // GeminiAIからのレスポンスを取得
    $responseText = Gemini::geminiPro()->generateContent($toGeminiCommand)->text();

    // 必要に応じてMarkdown形式に変換（Markdown形式で整形したい場合）
    $result = [
      'task' => $searchQuery,
      'content' => ($responseText),
    ];

    // ビューに結果を渡して表示
    return view('gemini.index', compact('result'));
  }

  public function save(Request $request)
  {
    // JSON形式の文字列として取得
    $recipes = $request->input('recipes');
    $selectedIndex = $request->input('selected_recipe');

    // 選択されたインデックスを使用して、選んだ料理を取得
    $selectedRecipeJson = $recipes[$selectedIndex] ?? null;

    if ($selectedRecipeJson) {
      // JSON形式の文字列をデコードして配列に変換
      $selectedRecipe = json_decode($selectedRecipeJson, true);

      // デコード結果を確認
      if (json_last_error() !== JSON_ERROR_NONE) {
        return back()->with('error', 'レシピの保存に失敗しました。JSONエラーが発生しました。');
      }

      $userId = auth()->id();
      if (!$userId) {
        return back()->with('error', 'レシピの保存に失敗しました。ユーザーが見つかりませんでした。');
      }
      // データベースにレシピを保存
      \App\Models\History::create([
        'user_id' => $userId,
        'name' => $selectedRecipe['料理名'],
        'ingredients' => json_encode($selectedRecipe['材料']),
      ]);

      // InventoryControllerから保存された在庫データを取得
      $inventories = Inventory::where('user_id', auth()->id())->get();

      return  view('gemini.inventory', compact('selectedRecipe', 'inventories'));
    }

    // return back()->with('error', 'レシピの保存に失敗しました。選択された料理が見つかりませんでした。');
  }

  public function update(Request $request)
  {
    // 入力された材料名と変更後数を取得
    $updatedCounts = $request->input('updated_count');

    foreach ($updatedCounts as $ingredientName => $updatedCount) {
      // 在庫アイテムを取得
      $inventoryItem = Inventory::where('name', $ingredientName)
        ->where('user_id', auth()->id()) // 現在のユーザーに関連する在庫アイテムを取得
        ->first();

      if ($inventoryItem) {
        // 既存の在庫アイテムが見つかった場合、変更後数で更新
        $inventoryItem->stock = $updatedCount;
        $inventoryItem->updated_at = now(); // 更新日時を変更
        $inventoryItem->save();
      } else {
        // 新しい材料の場合は新規作成
        DB::table('inventories')->insert([
          'name' => $ingredientName,
          'stock' => $updatedCount,
          'user_id' => auth()->id(), // 現在のユーザーIDを保存
          'created_at' => now(),
          'updated_at' => now(),
        ]);
      }
    }

    return redirect()->route('gemini.index')->with('success', '在庫が更新されました。');
  }
}
