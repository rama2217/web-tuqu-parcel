<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #F7F3EE; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #2D4A3E; padding: 30px; text-align: center; }
        .header h1 { color: #C9A96E; margin: 0; font-size: 24px; letter-spacing: 2px; }
        .body { padding: 30px; color: #333; }
        .card { background: #F7F3EE; padding: 20px; border-radius: 8px; border-left: 4px solid #C9A96E; margin: 20px 0; }
        .card p { margin: 8px 0; }
        .label { font-weight: bold; color: #2D4A3E; }
        .btn { display: inline-block; background: #2D4A3E; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; margin-top: 20px; font-weight: bold; }
        .footer { background: #F7F3EE; padding: 20px; text-align: center; color: #888; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>TuquParcel</h1>
        </div>
        <div class="body">
            <p>Halo Admin,</p>
            <p>Ada review baru yang menunggu persetujuan Anda.</p>

            <div class="card">
                <p><span class="label">Produk</span> : {{ $review->product->name ?? '-' }}</p>
                <p><span class="label">Nama</span> : {{ $review->reviewer_name }}</p>
                <p><span class="label">Rating</span> : {{ $review->rating }} / 5</p>
                <p><span class="label">Komentar</span> : {{ $review->comment }}</p>
                <p><span class="label">Waktu</span> : {{ $review->created_at->format('d M Y, H:i') }}</p>
            </div>

            <a href="{{ url('/admin/review') }}" class="btn">Lihat dan Setujui Review</a>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh sistem TuquParcel. Jangan balas email ini.
        </div>
    </div>
</body>
</html>
