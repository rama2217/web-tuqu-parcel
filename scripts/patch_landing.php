<?php
$file = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\pages\\landing.blade.php';
$content = file_get_contents($file);

$search = '/<div class="product-grid">(.*?)<\/div>\s*<\/div>\s*<\/section>/s';
$replace = <<<'EOD'
<div class="product-grid">
        @forelse($featuredProducts as $product)
        <div class="product-card reveal reveal-delay-{{ $loop->iteration <= 4 ? $loop->iteration : 1 }}">
          <div class="product-img">
            @if($product->images->first())
              <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" loading="lazy" />
            @else
              <img src="https://images.unsplash.com/photo-1565085558534-5b46b8bfcdcd?w=600&q=80" alt="Placeholder" loading="lazy" />
            @endif
            
            @if($product->stock == 0)
              <span class="product-badge" style="background:#1a1a1a;color:white;">Habis</span>
            @elseif($product->badge)
              <span class="product-badge {{ strtolower($product->badge) == 'limited' ? 'limited' : '' }}">{{ $product->badge }}</span>
            @endif
          </div>
          <div class="product-info">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-price">
              @if($product->discount_percent > 0)
                <span style="font-size:0.8rem;text-decoration:line-through;color:var(--text-muted);margin-right:6px;">{{ $product->formatted_price }}</span>
                <span style="color:var(--green-dark);font-weight:700;">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
              @else
                {{ $product->formatted_price }}
              @endif
            </div>
            <a href="{{ route('public.produk', $product->slug) }}" class="btn-detail">Lihat Detail</a>
          </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
          Belum ada produk unggulan.
        </div>
        @endforelse
      </div>
    </div>
  </section>
EOD;

$content = preg_replace($search, $replace, $content, 1);
file_put_contents($file, $content);
echo "Patch applied.";
