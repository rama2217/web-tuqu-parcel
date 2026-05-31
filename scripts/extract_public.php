<?php
$files = [
    'Katalog.html' => 'katalog.blade.php',
    'DetailProduk.html' => 'detail-produk.blade.php',
    'TentangKami.html' => 'tentang-kami.blade.php',
];

$baseHtmlPath = 'd:\\PROJECTS\\FrontendTuquParcel\\';
$baseBladePath = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\pages\\';

foreach($files as $html => $blade) {
    $contentHtml = file_get_contents($baseHtmlPath . $html);
    
    preg_match('/<style>(.*?)<\/style>/s', $contentHtml, $styleMatch);
    $style = isset($styleMatch[1]) ? $styleMatch[1] : '';

    // Hero + Features +... (Main Content)
    // Usually between </nav> and <footer>
    preg_match('/<\/nav>(.*)<footer/s', $contentHtml, $mainMatch);
    $mainContent = isset($mainMatch[1]) ? trim($mainMatch[1]) : '';

    $bladeHtml = "@extends('layouts.public')\n@section('title', 'TuquParcel')\n\n";
    if ($style) {
        $bladeHtml .= "@push('styles')\n<style>\n$style\n</style>\n@endpush\n\n";
    }
    
    $bladeHtml .= "@section('content')\n";
    $bladeHtml .= $mainContent;
    $bladeHtml .= "\n@endsection\n";

    file_put_contents($baseBladePath . $blade, $bladeHtml);
    echo "Extracted $html to $blade\n";
}
