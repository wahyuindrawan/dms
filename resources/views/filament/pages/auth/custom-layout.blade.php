<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Surat</title>

    {{-- Filament Styles --}}
    @filamentStyles
    @vite('resources/css/filament/admin/theme.css')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    {{ $slot }}

    {{-- Filament Scripts --}}
    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>