<?php
$file = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\pages\\detail-produk.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    'id="reviewer-name"',
    'id="reviewer-name" name="reviewer_name" required',
    $content
);
$content = str_replace(
    'id="reviewer-email"',
    'id="reviewer-email" name="email"',
    $content
);
$content = str_replace(
    'id="review-text"',
    'id="review-text" name="comment" required',
    $content
);

file_put_contents($file, $content);
echo "Inputs patched.\n";
