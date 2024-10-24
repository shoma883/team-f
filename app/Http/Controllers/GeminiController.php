<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory; // Inventoryモデルをインポート
use Gemini\Laravel\Facades\Gemini;

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

    // GeminiAPIからのレスポンスを取得
    $responseText = Gemini::geminiPro()->generateContent($toGeminiCommand)->text();

    // JSONをパース
    $cleanedContent = str_replace(['```', '```json'], '', $responseText);
    $dishes = json_decode($cleanedContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return back()->withErrors(['message' => 'Gemini APIからのレスポンスでエラーが発生しました。']);
    }

    // レスポンスをビューに渡す
    return view('gemini.index', compact('dishes'));
  }

  public function save(Request $request)
  {
    $recipes = $request->input('recipes');
    $selectedIndex = $request->input('selected_recipe');

    $selectedRecipeJson = $recipes[$selectedIndex] ?? null;

    if ($selectedRecipeJson) {
      $selectedRecipe = json_decode($selectedRecipeJson, true);
      if (json_last_error() !== JSON_ERROR_NONE) {
        return back()->with('error', 'レシピの保存に失敗しました。JSONエラーが発生しました。');
      }

      // レシピを履歴に保存
      \App\Models\History::create([
        'user_id' => auth()->id(),
        'name' => $selectedRecipe['料理名'],
        'ingredients' => json_encode($selectedRecipe['材料']),
      ]);

      return redirect()->route('gemini.index')->with('success', 'レシピが保存されました。');
    }

    return back()->with('error', '選択されたレシピが見つかりませんでした。');
  }
}
