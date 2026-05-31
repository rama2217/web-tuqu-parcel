<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — TuquParcel</title>
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
    <p class="text-center text-sm mb-3" style="color:#C9A96E">Buat Password Baru</p>

    <div class="bg-white rounded-2xl shadow-lg p-8">

        @if ($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#2D4A3E">Email</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" required
                    placeholder="contoh@email.com"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none transition"
                    onfocus="this.style.borderColor='#2D4A3E'" onblur="this.style.borderColor='#e5e7eb'">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#2D4A3E">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="pass1" required
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-200 text-sm outline-none transition"
                        onfocus="this.style.borderColor='#2D4A3E'" onblur="this.style.borderColor='#e5e7eb'">
                    <button type="button" onclick="togglePass('pass1')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#2D4A3E">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="pass2" required
                        placeholder="Ulangi password baru"
                        class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-200 text-sm outline-none transition"
                        onfocus="this.style.borderColor='#2D4A3E'" onblur="this.style.borderColor='#e5e7eb'">
                    <button type="button" onclick="togglePass('pass2')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full py-3.5 rounded-xl text-white font-medium text-sm transition hover:opacity-90 active:scale-95"
                style="background-color:#2D4A3E">
                Reset Password
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} TuquParcel. All rights reserved.</p>
</div>

<script>
    function togglePass(id) {
        const el = document.getElementById(id);
        el.type = el.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>
