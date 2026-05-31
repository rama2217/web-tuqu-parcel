<?php
$viewsDir = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\';
$files = [
    $viewsDir . 'layouts\\public.blade.php',
    $viewsDir . 'pages\\landing.blade.php',
    $viewsDir . 'pages\\katalog.blade.php',
    $viewsDir . 'pages\\detail-produk.blade.php',
    $viewsDir . 'pages\\tentang-kami.blade.php'
];

$replacements = [
    'LandingPage.html' => '{{ route(\'public.landing\') }}',
    'Katalog.html' => '{{ route(\'public.katalog\') }}',
    'TentangKami.html' => '{{ route(\'public.tentang\') }}',
    'tuquparcel-homepage.html' => '{{ route(\'public.landing\') }}',
    'tuquparcel-catalog.html' => '{{ route(\'public.katalog\') }}',
    'DetailProduk.html' => '#', // since detail requires a slug, hard to replace globally if they are dummy links
    'href="#"' => 'href="javascript:void(0)"', // Prevent jumps on dummy links
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        file_put_contents($file, $content);
        echo "Patched links in: " . basename($file) . "\n";
    } else {
        echo "File not found: " . basename($file) . "\n";
    }
}
