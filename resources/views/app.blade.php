<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'NDZ') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('vite.svg') }}">
    @php
        // Prefer the Vite dev server in local env so npm run dev auto-refreshes
        $devServerUrl = env('FRONTEND_DEV_SERVER', 'http://localhost:5173');
        $useDevServer = app()->environment('local') && env('FRONTEND_DEV', true);
        if ($useDevServer) {
            $host = parse_url($devServerUrl, PHP_URL_HOST) ?? 'localhost';
            $port = parse_url($devServerUrl, PHP_URL_PORT) ?? 5173;
            $connection = @fsockopen($host, $port, $errno, $errstr, 0.1);
            if ($connection) {
                fclose($connection);
            } else {
                $useDevServer = false;
            }
        }

        $assetDir = public_path('assets');
        $jsFiles = is_dir($assetDir) ? glob($assetDir . '/index-*.js') : [];
        $cssFiles = is_dir($assetDir) ? glob($assetDir . '/index-*.css') : [];
        $latestJs = collect($jsFiles)->sortByDesc(fn ($path) => filemtime($path))->first();
        $latestCss = collect($cssFiles)->sortByDesc(fn ($path) => filemtime($path))->first();
        $jsEntry = $latestJs ? basename($latestJs) : '';
        $cssEntry = $latestCss ? basename($latestCss) : '';
    @endphp
    @if ($useDevServer)
        <script type="module" src="{{ rtrim($devServerUrl, '/') }}/@@vite/client"></script>
    @elseif ($cssEntry)
        <link rel="stylesheet" href="{{ asset('assets/' . $cssEntry) }}" crossorigin>
    @endif
</head>
<body>
    <div id="app"></div>

    @if ($useDevServer)
        <script type="module" src="{{ rtrim($devServerUrl, '/') }}/src/main.js"></script>
    @elseif ($jsEntry)
        <script type="module" src="{{ asset('assets/' . $jsEntry) }}" crossorigin></script>
    @else
        <p style="text-align:center;margin-top:2rem;">Build the frontend bundle by running <code>npm install</code> and <code>npm run build</code> inside the <code>frontend</code> directory.</p>
    @endif
</body>
</html>
