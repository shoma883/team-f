<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

use Illuminate\Support\Str;

class GeminiController extends Controller
{
    public function index()
    {
        return view('inventories.index');
    }

    public function entry(Request $request)
    {
        // ユーザーからの入力（例：「簡単な料理」など）
        $searchQuery = $request->toGeminiText;

        // GeminiAIに渡すプロンプトを生成
        $toGeminiCommand = "料理を提案してください '{$searchQuery}'。材料を個数のみでリストしてください。";

        // GeminiAIからのレスポンスを取得
        $responseText = Gemini::geminiPro()->generateContent($toGeminiCommand)->text();

        // 必要に応じてMarkdown形式に変換（Markdown形式で整形したい場合）
        $result = [
            'task' => $searchQuery,
            'content' => Str::markdown($responseText),
        ];

        // ビューに結果を渡して表示
        return view('inventories.index', compact('result'));
    }
}
