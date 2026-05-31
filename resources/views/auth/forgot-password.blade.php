<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — TuquParcel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #F7F3EE; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4">
<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="text-center mb-4">
        <a href="{{ route('public.landing') }}" style="display:inline-flex;align-items:center;gap:14px;text-decoration:none;">
            @php $logo = App\Models\Setting::get('site_logo_navbar') ?: App\Models\Setting::get('site_logo'); @endphp
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="TuquParcel" style="height:52px;width:52px;object-fit:contain;flex-shrink:0;">
            @endif
            <h1 class="text-3xl font-bold" style="font-family:'Cormorant Garamond',serif; color:#2D4A3E">TUQU PARCEL</h1>
        </a>
    </div>
    <p class="text-center text-sm mb-3" style="color:#C9A96E">Reset Password</p>

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <p class="text-sm text-gray-500 mb-6">
            Masukkan email yang terdaftar. Kami akan mengirimkan link untuk mereset password kamu.
        </p>

        @if(session('success'))
            <div class="mb-5 p-4 rounded-xl text-sm text-green-700 bg-green-50 border border-green-200">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#2D4A3E">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="contoh@email.com"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none transition"
                    onfocus="this.style.borderColor='#2D4A3E'" onblur="this.style.borderColor='#e5e7eb'">
            </div>

            <button type="submit"
                class="w-full py-3.5 rounded-xl text-white font-medium text-sm transition hover:opacity-90 active:scale-95"
                style="background-color:#2D4A3E">
                Kirim Link Reset Password
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Ingat password?
            <a href="{{ route('login') }}" class="font-medium hover:underline" style="color:#C9A96E">Masuk di sini</a>
        </p>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} TuquParcel. All rights reserved.</p>
</div>
</body>
</html>
