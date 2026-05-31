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
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #2D4A3E; color: white; padding: 12px; text-align: left; font-weight: normal; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .low { color: #D97706; font-weight: bold; }
        .empty { color: #DC2626; font-weight: bold; }
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
            <p>Produk berikut memiliki stok rendah atau habis dan perlu segera diperbarui.</p>

            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>SKU</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td class="{{ $product->stock == 0 ? 'empty' : 'low' }}">
                            {{ $product->stock == 0 ? 'Habis' : $product->stock . ' tersisa' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ url('/admin/inventaris') }}" class="btn">Kelola Inventaris</a>
        </div>
        <div class="footer">
            Email ini dikirim otomatis oleh sistem TuquParcel. Jangan balas email ini.
        </div>
    </div>
</body>
</html>
