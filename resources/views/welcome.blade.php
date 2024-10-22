<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  <!-- Styles -->
  <style>
    a {
      text-decoration: none;
    }

    /* Typography ===== */
    /* Font Size */
    .text-2xl {
      font-size: 2rem;
    }

    .text-10xl {
      font-size: 10rem;
    }

    /* Colors */
    .dark\:text-gray-400 {
      color: rgba(156, 163, 175);
    }

    .dark\:text-white {
      color: #f9fafb;
    }

    /* Sizing */
    .w-1\/4 {
      width: 25%;
    }

    .h-screen {
      height: 100vh;
    }

    /* FlexBox */
    .flex {
      display: flex;
    }

    .column {
      flex-direction: column;
    }

    .items-center {
      align-items: center;
    }

    .justify-center {
      justify-content: center;
    }

    .justify-between {
      justify-content: space-between;
    }

    /* Backgrounds */
    .bg-gray-100 {
      background-color: #f3f4f6;
    }

    .dark\:bg-gray-900 {
      background-color: #1f2937;
    }
  </style>
</head>

<body class="">
  <header class="">
  </header>

  <main class="">
    <div class="flex column items-center justify-center h-screen bg-gray-100 dark:bg-gray-900">
      <h1 class="text-10xl dark:text-white">APP NAME</h1>
      @if (Route::has('login'))
        @auth
          <nav class="text-2xl">
            <a href="{{ url('/gemini') }}" class="dark:text-gray-400">
              Home
            </a>
          </nav>
        @else
          <nav class="flex justify-between text-2xl w-1/4">
            <a href="{{ route('login') }}" class="dark:text-gray-400">
              Log in
            </a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="dark:text-gray-400">
                Register
              </a>
            @endif
          </nav>
        @endauth
      @endif
    </div>
  </main>

  <footer class="">
  </footer>
</body>

</html>
