<?php
$htmlPath = 'd:\\PROJECTS\\FrontendTuquParcel\\tambahproduk.html';
$html = file_get_contents($htmlPath);

preg_match('/<style>(.*?)<\/style>/s', $html, $styleMatch);
$style = isset($styleMatch[1]) ? $styleMatch[1] : '';

preg_match('/<div class="content">(.*?)<\/div><!-- \/content -->/s', $html, $contentMatch);
$content = isset($contentMatch[1]) ? $contentMatch[1] : '';

$blade = "@extends('layouts.admin')\n@section('title', 'Tambah Produk')\n\n";
$blade .= "@push('styles')\n<style>\n$style\nx\n</style>\n@endpush\n\n";
$blade .= "@section('content')\n";
$blade .= "<form method=\"POST\" action=\"{{ route('admin.inventaris.store') }}\" enctype=\"multipart/form-data\">\n";
$blade .= "  @csrf\n";
$blade .= "  @if(\$errors->any())\n    <div style=\"background:#fff0f0;border:1px solid #f5c6c0;border-radius:10px;padding:14px 18px;margin-bottom:18px;font-size:13.5px;color:#c0392b;\">\n      <ul style=\"list-style:disc;padding-left:18px;\">\n        @foreach(\$errors->all() as \$e)<li>{{ \$e }}</li>@endforeach\n      </ul>\n    </div>\n  @endif\n\n";
$blade .= $content;
$blade .= "\n</form>\n";
$blade .= "@endsection\n";
$blade .= "@push('scripts')\n<script>\nfunction addTag() { /* Add specific logic later */ }\n</script>\n@endpush\n";

file_put_contents('d:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\admin\\inventaris\\tambah.blade.php', $blade);
echo "Berhasil update tambah.blade.php\n";
