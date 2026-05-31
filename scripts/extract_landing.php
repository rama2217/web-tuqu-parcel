<?php
$htmlPath = 'd:\\PROJECTS\\FrontendTuquParcel\\LandingPage.html';
$contentHtml = file_get_contents($htmlPath);

preg_match('/<style>(.*?)<\/style>/s', $contentHtml, $styleMatch);
$style = isset($styleMatch[1]) ? $styleMatch[1] : '';

// Navbar
preg_match('/<nav(.*?)<\/nav>/s', $contentHtml, $navMatch);
$nav = isset($navMatch[0]) ? $navMatch[0] : '';

// Hero + Features +... (Main Content)
// Usually between </nav> and <footer>
preg_match('/<\/nav>(.*)<footer/s', $contentHtml, $mainMatch);
$mainContent = isset($mainMatch[1]) ? trim($mainMatch[1]) : '';

// Footer
preg_match('/<footer(.*?)<\/footer>/s', $contentHtml, $footerMatch);
$footer = isset($footerMatch[0]) ? $footerMatch[0] : '';


// Generate layout public
$layoutBlade = "<!DOCTYPE html>
<html lang=\"id\">
<head>
  <meta charset=\"UTF-8\" />
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />
  <title>@yield('title', 'TuquParcel')</title>
  <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\" />
  <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin />
  <link href=\"https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap\" rel=\"stylesheet\" />
  <style>
$style
  @media (max-width: 768px) {
    .mobile-menu { display: none; }
    .mobile-menu.open { display: flex; flex-direction: column; }
  }
  </style>
  @stack('styles')
</head>
<body>

$nav

@yield('content')

$footer

<script>
  // Mobile Menu
  const btn = document.getElementById('mobile-menu-btn');
  const menu = document.getElementById('mobile-menu');
  if(btn) {
      btn.addEventListener('click', () => {
        btn.classList.toggle('open');
        menu.classList.toggle('open');
      });
  }
  // Sticky Nav
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
</script>
@stack('scripts')
</body>
</html>";

file_put_contents('d:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\layouts\\public.blade.php', $layoutBlade);

// Generate Landing Page
$landingBlade = "@extends('layouts.public')\n@section('title', 'TuquParcel — Beranda')\n@section('content')\n";
$landingBlade .= $mainContent;
$landingBlade .= "\n@endsection\n";
file_put_contents('d:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\pages\\landing.blade.php', $landingBlade);

echo "Layout and Landing updated.\n";
