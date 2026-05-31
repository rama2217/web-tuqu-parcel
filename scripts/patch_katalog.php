<?php
$file = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\pages\\katalog.blade.php';
$content = file_get_contents($file);

$searchGrid = '/<div class="product-grid">.*?<\/div>\s*<!-- PAGINATION -->/s';
$replaceGrid = <<<'EOD'
<div class="product-grid">
            @forelse($products as $product)
            <a href="{{ route('public.produk', $product->slug) }}" class="product-card reveal reveal-delay-1">
              <div class="product-img">
                @if($product->images->first())
                  <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" loading="lazy" />
                @else
                  <img src="https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=600&q=80" alt="Placeholder" loading="lazy" />
                @endif
                
                @if($product->stock == 0)
                  <div class="badge-out"><span>Out of Stock</span></div>
                @elseif($product->badge)
                  <span class="badge {{ strtolower($product->badge) == 'sale' ? 'badge-sale' : 'badge-new' }}">{{ $product->badge }}</span>
                @endif
              </div>
              <div class="product-info">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-price">
                  @if($product->discount_percent > 0)
                    <span class="price-original">{{ $product->formatted_price }}</span>
                    <span class="price-discount">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                  @else
                    {{ $product->formatted_price }}
                  @endif
                </div>
              </div>
            </a>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
              Belum ada produk untuk kategori ini.
            </div>
            @endforelse
          </div>

          <!-- PAGINATION -->
EOD;

$content = preg_replace($searchGrid, $replaceGrid, $content, 1);

$searchPagination = '/<div class="pagination reveal">.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<!-- FOOTER -->/s';
$replacePagination = <<<'EOD'
<div class="pagination reveal" style="margin-top: 24px; padding-bottom: 24px;">
            {{ $products->links('pagination::semantic-ui') }}
          </div>
        </div>
      </div>
    </div>

    <!-- FOOTER -->
EOD;

$content = preg_replace($searchPagination, $replacePagination, $content, 1);

file_put_contents($file, $content);
echo "Katalog patched.\n";
