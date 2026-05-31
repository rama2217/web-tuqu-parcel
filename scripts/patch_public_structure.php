<?php
$viewsDir = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\pages\\';
$files = ['landing.blade.php', 'katalog.blade.php', 'detail-produk.blade.php', 'tentang-kami.blade.php'];

foreach ($files as $file) {
    $path = $viewsDir . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Hapus elemen doctype, html, head, title, body jika masih ada
        $content = preg_replace('/<!doctype html>/i', '', $content);
        $content = preg_replace('/<!DOCTYPE html>/i', '', $content);
        $content = preg_replace('/<html lang="id">/i', '', $content);
        $content = preg_replace('/<html lang="en">/i', '', $content);
        $content = preg_replace('/<head>.*?<\/head>/is', '', $content);
        $content = preg_replace('/<body>/i', '', $content);
        $content = preg_replace('/<\/body>/i', '', $content);
        $content = preg_replace('/<\/html>/i', '', $content);
        
        // Pastikan extends dan section title ada di awal
        if (strpos($content, "@extends('layouts.public')") === false) {
             $content = "@extends('layouts.public')\n@section('title', 'TuquParcel')\n" . $content;
        }
        
        // Pastikan section content membungkus isi
        if (strpos($content, "@section('content')") === false) {
             // Cari dimana awal content sebenarnya
             if (preg_match('/(@push\(\'styles\'\).*?@endpush)/s', $content, $matches)) {
                  $pushStyle = $matches[0];
                  $content = str_replace($pushStyle, '', $content);
                  $content = preg_replace('/(@section\(\'title\'.*?\n)/', "$1" . $pushStyle . "\n@section('content')\n", $content, 1);
             } else {
                  $content = preg_replace('/(@section\(\'title\'.*?\n)/', "$1@section('content')\n", $content, 1);
             }
             
             $content .= "\n@endsection\n";
        }
        
        file_put_contents($path, trim($content));
        echo "Cleaned structure in: $file\n";
    }
}
