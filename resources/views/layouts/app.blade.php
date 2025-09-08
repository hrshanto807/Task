<!DOCTYPE html>
<html>
<head>
    <title>E-commerce</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.output.css') }}" />

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto mt-6">
        @yield('content')
    </div>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
