<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Poliklinik' }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="bg-slate-50 p-8">

    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-slate-800 mb-1">
            Selamat Datang, {{ auth()->user()->name ?? 'Dokter' }} 👋
        </h2>
        <p class="text-sm text-slate-400">
            {{ now()->translatedFormat('l, d F Y') }} - Berikut ringkasan aktivitas praktik Anda hari ini.
        </p>
    </div>

</body>
</html>