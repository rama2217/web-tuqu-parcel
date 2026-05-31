<?php
$viewsDir = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\pages\\';
$files = ['landing.blade.php', 'katalog.blade.php', 'detail-produk.blade.php', 'tentang-kami.blade.php'];

foreach ($files as $file) {
    $path = $viewsDir . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Find <style> and move it into @push('styles') ... @endpush if not already
        if (strpos($content, "@push('styles')") === false && strpos($content, '<style>') !== false) {
             $content = preg_replace('/(<style>.*?<\/style>)/s', "@push('styles')\n$1\n@endpush\n", $content, 1);
             
             // Move the @push block up right after @section('title')
             if (preg_match('/(@push\(\'styles\'\).*?@endpush)/s', $content, $matches)) {
                 $pushBlock = $matches[1];
                 $content = str_replace($pushBlock, '', $content);
                 $content = preg_replace('/(@section\(\'title\'.*?\n)/', "$1\n" . $pushBlock . "\n", $content, 1);
             }
             
             file_put_contents($path, $content);
             echo "Patched styles in: $file\n";
        }
        
        // Find scripts at the bottom and push them
        if (strpos($content, "@push('scripts')") === false && strpos($content, '<script>') !== false) {
            $content = preg_replace('/(<script>.*?<\/script>)/s', "@push('scripts')\n$1\n@endpush\n", $content, 1);
            file_put_contents($path, $content);
            echo "Patched scripts in: $file\n";
        }
    }
}
