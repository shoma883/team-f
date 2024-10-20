<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    // ユーザーからの入力（例：「簡単な料理」など）
    $searchQuery = $request->toGeminiText;


        // GeminiAIに渡すプロンプトを生成
        $toGeminiCommand = "料理名とその食材、個数をJSON形式で5つ分出力してください。'{$searchQuery}'。すべての数量は**整数のみ**で、調味料は含めないでください。単位は表示しないでください。以下の形式で返答してください:

    // GeminiAIに渡すプロンプトを生成
    $toGeminiCommand = "料理名とその食材、個数をJSON形式で出力してください。'{$searchQuery}'。すべての数量は**数値のみ**で、単位は表示しないでください。以下の形式で返答してください:


        {
            \"料理\": [
                {
                    \"料理名\": \"料理1\",
                    \"材料\": [
                        { \"材料名\": \"材料1\", \"個数\": 数量 },
                        { \"材料名\": \"材料2\", \"個数\": 数量 }
                    ]
                },
                {
                    \"料理名\": \"料理2\",
                    \"材料\": [
                        { \"材料名\": \"材料1\", \"個数\": 数量 },
                        { \"材料名\": \"材料2\", \"個数\": 数量 }
                    ]
                },
                {
                    \"料理名\": \"料理3\",
                    \"材料\": [
                        { \"材料名\": \"材料1\", \"個数\": 数量 },
                        { \"材料名\": \"材料2\", \"個数\": 数量 }
                    ]
                },
                {
                    \"料理名\": \"料理4\",
                    \"材料\": [
                        { \"材料名\": \"材料1\", \"個数\": 数量 },
                        { \"材料名\": \"材料2\", \"個数\": 数量 }
                    ]
                },
                {
                    \"料理名\": \"料理5\",
                    \"材料\": [
                        { \"材料名\": \"材料1\", \"個数\": 数量 },
                        { \"材料名\": \"材料2\", \"個数\": 数量 }
                    ]
                }
            ]
        }";



        
        //GeminiAIからのレスポンスを取得
         
        $responseText = Gemini::geminiPro()->generateContent($toGeminiCommand)->text();
        //dd($responseText);

        $result = [
            'task' => $searchQuery,
            'content' => ($responseText),
        ];

        //($result);


        // JSONデコード
        
        $dishes = json_decode($result['content'], true);

        // デコード結果を確認
        if (json_last_error() !== JSON_ERROR_NONE) {
            dd('JSONエラー: ' . json_last_error_msg());
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

    dd($result);

    // ビューに結果を渡して表示
    return view('gemini.index', compact('result'));
  }

  public function save(Request $request)
  {
    $recipes = json_encode($request->input('recipes'), true);
    $selectedIndex = $request->input('selected_recipe');

    $selectedRecipe = $recipes[$selectedIndex];

    // レシピの保存処理
    if ($selectedRecipe) {
      \App\Models\History::create([
        'name' => $selectedRecipe['料理名'],
        'ingredients' => json_encode($selectedRecipe['材料']),
      ]);

      return redirect()->route('gemini.index')->with('success', '選択したレシピを保存しました。');

    }

    return back()->with('error', 'レシピの保存に失敗しました。');
  }
}
