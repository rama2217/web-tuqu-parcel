<?php
$viewsDir = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\pages\\';
$files = ['landing.blade.php', 'katalog.blade.php', 'detail-produk.blade.php', 'tentang-kami.blade.php'];

foreach ($files as $file) {
    $path = $viewsDir . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        $lines = explode("\n", $content);
        $newLines = [];
        $inStyle = false;
        $inScript = false;
        
        foreach ($lines as $line) {
            // styles
            if (strpos($line, '<style>') !== false && strpos($line, "@push('styles')") === false) {
                $newLines[] = "@push('styles')";
                $newLines[] = $line;
                $inStyle = true;
                continue;
            }
            if (strpos($line, '</style>') !== false && $inStyle) {
                $newLines[] = $line;
                $newLines[] = "@endpush";
                $inStyle = false;
                continue;
            }
            
            // scripts
            if (strpos($line, '<script>') !== false && strpos($line, "@push('scripts')") === false) {
                $newLines[] = "@push('scripts')";
                $newLines[] = $line;
                $inScript = true;
                continue;
            }
            if (strpos($line, '</script>') !== false && $inScript) {
                $newLines[] = $line;
                $newLines[] = "@endpush";
                $inScript = false;
                continue;
            }
            
            $newLines[] = $line;
        }
        
        file_put_contents($path, implode("\n", $newLines));
        echo "Patched tags in: $file\n";
    }
}
