<?php
$files = [
    'editproduk.html' => 'resources/views/admin/inventaris/edit.blade.php',
    'review.html' => 'resources/views/admin/review/index.blade.php',
    'pengaturan.html' => 'resources/views/admin/pengaturan/index.blade.php',
];

$baseHtmlPath = 'd:\\PROJECTS\\FrontendTuquParcel\\';
$baseBladePath = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\';

foreach($files as $html => $blade) {
    $contentHtml = file_get_contents($baseHtmlPath . $html);
    preg_match('/<style>(.*?)<\/style>/s', $contentHtml, $styleMatch);
    $style = isset($styleMatch[1]) ? $styleMatch[1] : '';

    preg_match('/<div class="content">(.*?)<\/div><!-- \/content -->/s', $contentHtml, $contentMatch);
    if (!isset($contentMatch[1])) {
        // Fallback for files without inner content comment
        preg_match('/<div class="content">(.*?)<\/div>\s*<\/div>\s*<!-- BOTTOM BAR -->/s', $contentHtml, $contentMatch);
    }
    if (!isset($contentMatch[1])) {
         // general fallback
         preg_match('/<div class="content">(.*)/s', $contentHtml, $contentMatch);
    }
    
    $content = isset($contentMatch[1]) ? $contentMatch[1] : '';
    // Strip ending tags if we matched till end
    $content = preg_replace('/<\/div><!-- \/main -->.*/s', '', $content);
    $content = preg_replace('/<script>.*/s', '', $content);
    $content = preg_replace('/<\/div>\s*<\/body>\s*<\/html>/s', '', $content);

    $bladeHtml = "@extends('layouts.admin')\n@section('title', 'Admin Panel')\n\n";
    if ($style) {
        $bladeHtml .= "@push('styles')\n<style>\n$style\n</style>\n@endpush\n\n";
    }
    
    $bladeHtml .= "@section('content')\n";
    $bladeHtml .= $content;
    $bladeHtml .= "\n@endsection\n";

    file_put_contents($baseBladePath . $blade, $bladeHtml);
    echo "Extracted $html to $blade\n";
}
