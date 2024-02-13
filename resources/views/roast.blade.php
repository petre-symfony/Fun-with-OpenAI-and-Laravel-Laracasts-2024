<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>AI Poems</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full grid place-items-center">
<form action="/roast" method="POST" class="w-full">
  <input type="text" placeholder="What do you want us to roast?" required class="border p-2 rounded">

  <button
      type="submit"
      class="rounded p-2 bg-gray-200 hover:bg-blue-500 hover:text-white"
  >
    Roast
  </button>
</form>
</body>
</html>
