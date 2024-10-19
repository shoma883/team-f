<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}}</title>

    <style>
        body {background: #bae6fd;}
        h1 {margin: 30px 10px;}
        h2 {margin: 30px 10px;}
        form {display: flex; align-items: flex-end; margin: 0 0 20px 10px}
        textarea {margin-right: 10px; padding: 5px 10px; width: 200px; height: 100px;}
        button {padding: 5px 10px;height: 30px;}
        p {margin: 5px 0 0 10px;}
    </style>
</head>
<body>
    <h1>{{config('app.name')}}</h1>
    
    <form action="{{route('entry')}}" method="post">
        @csrf
        <textarea name="toGeminiText" autofocus>@isset($result['task']){{$result['task']}}@endisset </textarea>
        <button type="submit">send</button>
    </form>
    
    <hr>

    @isset($result)
    <p>{!!$result['content']!!}</p>
    @endisset
</body>
</html>