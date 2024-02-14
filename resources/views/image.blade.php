<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>AI Image generation</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-slate-100">
  <div class="flex gap-6 mx-auto max-w-3xl bg-white py-6 px-10 rounded-xl">
    <div>
      <h1 class="font-bold mb-4">Generate an Image</h1>

      <form method="POST" action="/image">
        @csrf

        <textarea
            name="description"
            id="description"
            cols="30"
            rows="5"
            class="border border-gray-600 rounded text-xs p-2"
            placeholder="A beagle barking at a sqirrel in a tree ..."
        ></textarea>

        <p class="mt-2">
          <button class="border border-black px-2 rounded hover:bg-blue-500 hover:text-white">Submit</button>
        </p>
      </form>
    </div>

    <div>
      @if (count($messages))
        @foreach($messages as $message)
          <p>{{ $message['content'] }}</p>
        @endforeach
      @else
        <p>No vizualization yet</p>
      @endif
    </div>
  </div>
</body>
</html>