<?php
$file = 'd:\\PROJECTS\\FrontendTuquParcel\\TuquParcel\\resources\\views\\pages\\detail-produk.blade.php';
$content = file_get_contents($file);

// Breadcrumb
$content = str_replace(
    '<span class="current">Gift Parcels</span>',
    '<span class="current">{{ $product->name }}</span>',
    $content
);
$content = str_replace(
    '<a href="tuquparcel-homepage.html">Home</a>',
    '<a href="{{ route(\'public.landing\') }}">Home</a>',
    $content
);
$content = str_replace(
    '<a href="tuquparcel-catalog.html">Catalog</a>',
    '<a href="{{ route(\'public.katalog\') }}">Catalog</a>',
    $content
);

// Gallery Main Image & Thumbnails
$searchGallery = '/<div class="gallery reveal">.*?<\/div>\s*<!-- PRODUCT INFO -->/s';
$replaceGallery = <<<'EOD'
<div class="gallery reveal">
            <div class="main-image" id="mainImage">
              @php $mainImage = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
              <img
                src="{{ $mainImage ? $mainImage->url : 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=900&q=80' }}"
                alt="{{ $product->name }}"
                id="mainImg"
              />
              @if($product->badge)
              <span class="badge-terlaris" {{ strtolower($product->badge) == 'sale' ? 'style="background:#c0392b;"' : '' }}>{{ $product->badge }}</span>
              @endif
            </div>
            <div class="thumbnails">
              @foreach($product->images as $index => $image)
              <div
                class="thumb {{ $index == 0 ? 'active' : '' }}"
                onclick="changeImage(this, '{{ $image->url }}')"
              >
                <img
                  src="{{ $image->url }}"
                  alt="{{ $product->name }} {{ $index+1 }}"
                />
              </div>
              @endforeach
            </div>
          </div>

          <!-- PRODUCT INFO -->
EOD;
$content = preg_replace($searchGallery, $replaceGallery, $content, 1);

// Product Info (Category, Title, Rating, Price, Desc)
$searchInfo = '/<div class="product-info reveal">.*?<!-- Parcel Contents -->/s';
$replaceInfo = <<<'EOD'
<div class="product-info reveal">
            <div class="product-category">{{ $product->category->name ?? 'Kategori' }}</div>
            <h1 class="product-title">{{ $product->name }}</h1>

            <div class="rating-row">
              <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                  <span class="star" {!! $i > $product->stars ? 'style="color:var(--border)"' : '' !!}>★</span>
                @endfor
              </div>
              <span class="rating-text">{{ number_format($product->stars, 1) }} ({{ $product->reviews()->where('is_approved', true)->count() }} ulasan)</span>
            </div>

            <div class="price-row">
              @if($product->discount_percent > 0)
                <span class="price-main">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                <span class="price-original">{{ $product->formatted_price }}</span>
                <span class="price-badge">-{{ $product->discount_percent }}%</span>
              @else
                <span class="price-main">{{ $product->formatted_price }}</span>
              @endif
            </div>

            <p class="product-desc">
              {{ $product->description }}
            </p>

            <!-- Parcel Contents -->
EOD;
$content = preg_replace($searchInfo, $replaceInfo, $content, 1);

// Parcel Contents List
$searchContents = '/<div class="contents-box">.*?<\/div>\s*<!-- CTA -->/s';
$replaceContents = <<<'EOD'
<div class="contents-box">
              <div class="contents-title">Isi Parcel</div>
              <ul class="contents-list">
                @forelse($product->contents as $contentItem)
                <li>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                  </svg>
                  {{ $contentItem->item }}
                </li>
                @empty
                <li>Tidak ada rincian isi parcel.</li>
                @endforelse
              </ul>
            </div>

            <!-- CTA -->
EOD;
$content = preg_replace($searchContents, $replaceContents, $content, 1);

// You May Also Like
$searchAlso = '/<div class="also-grid">.*?<\/section>\s*<!-- REVIEWS -->/s';
$replaceAlso = <<<'EOD'
<div class="also-grid">
          @foreach(\App\Models\Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->take(4)->get() as $related)
          <a href="{{ route('public.produk', $related->slug) }}" class="also-card reveal reveal-delay-{{ $loop->iteration }}">
            <div class="also-img">
              <img src="{{ $related->images->first() ? $related->images->first()->url : '' }}" alt="{{ $related->name }}" loading="lazy" />
            </div>
            <div class="also-info">
              <div class="also-name">{{ $related->name }}</div>
              <div class="also-type">{{ $related->category->name ?? '' }}</div>
              <div class="also-bottom">
                <span class="also-price">{{ $related->formatted_price }}</span>
              </div>
            </div>
          </a>
          @endforeach
        </div>
      </div>
    </section>

    <!-- REVIEWS -->
EOD;
$content = preg_replace($searchAlso, $replaceAlso, $content, 1);

// Reviews List
$searchReviews = '/<div class="reviews-grid">.*?<\/div>\s*<\/div>\s*<\/section>\s*<!-- REVIEW FORM -->/s';
$replaceReviews = <<<'EOD'
<div class="reviews-grid">
          @forelse($product->reviews()->where('is_approved', true)->latest()->take(3)->get() as $review)
          <div class="review-card reveal reveal-delay-{{ $loop->iteration }}">
            <div class="review-header">
              <div class="review-stars">
                @for($i = 1; $i <= 5; $i++)
                  <span class="review-star" {!! $i > $review->rating ? 'style="color:var(--border)"' : '' !!}>★</span>
                @endfor
              </div>
              <span class="reviewer-name">{{ $review->reviewer_name }}</span>
            </div>
            <p class="review-text">"{{ $review->comment }}"</p>
          </div>
          @empty
          <p style="grid-column: 1 / -1; color: var(--text-muted);">Belum ada ulasan untuk produk ini. Jadilah yang pertama!</p>
          @endforelse
        </div>
      </div>
    </section>

    <!-- REVIEW FORM -->
EOD;
$content = preg_replace($searchReviews, $replaceReviews, $content, 1);

// Form route
$content = str_replace('<div class="review-form-card">', '<form action="'. "{{ route('public.review.store') }}" .'" method="POST" class="review-form-card">'."\n@csrf\n<input type=\"hidden\" name=\"product_id\" value=\"{{ \$product->id }}\">", $content);
$content = str_replace('<button class="btn-submit" onclick="submitReview()">', '<button type="submit" class="btn-submit">', $content);
$content = str_replace('</div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->', '</form>
        </div>
      </div>
    </section>

    <!-- FOOTER -->', $content);

file_put_contents($file, $content);
echo "Detail patch applied.";
