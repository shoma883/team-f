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
        $toGeminiCommand = "料理名とその食材、個数をJSON形式で出力してください。'{$searchQuery}'。すべての数量は**数値のみ**で、単位は表示しないでください。以下の形式で返答してください:

        {
            \"料理名\": \"料理名\",
            \"材料\": [
                { \"材料名\": \"材料1\", \"個数\": 数量 },
                { \"材料名\": \"材料2\", \"個数\": 数量 }
            ]
        }
        ";


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
}
