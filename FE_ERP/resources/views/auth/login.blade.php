<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IzinXML</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex items-center justify-center bg-gradient-to-br from-[#FCEABB] to-[#ADD8F6]">

    <div class="w-full max-w-md bg-white/80 backdrop-blur-sm shadow-lg rounded-xl p-8">
        {{-- Logo + Judul --}}
        <div class="flex flex-col items-center mb-6">
            <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" class="w-12 h-12 mb-2" alt="logo">
            <h2 class="text-2xl font-bold text-[#4764F3]">IzinXML</h2>
        </div>

        {{-- Pesan Error --}}
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form Login --}}
        <form method="POST" action="{{ route('login.process') }}">
            @csrf
            <div class="mb-4 relative">
                <span class="absolute left-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A8 8 0 1116.97 6.05a8 8 0 01-11.849 11.754z" />
                    </svg>
                </span>
                <input type="text" name="nama" placeholder="nama"
                       class="w-full pl-10 p-2 border rounded-full focus:ring-2 focus:ring-[#4764F3]" required>
            </div>

            <div class="mb-4 relative">
                <span class="absolute left-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c.828 0 1.5.672 1.5 1.5S12.828 14 12 14s-1.5-.672-1.5-1.5S11.172 11 12 11zm0 0V8m0 3a4.5 4.5 0 10-9 0v1h18v-1a4.5 4.5 0 10-9 0z" />
                    </svg>
                </span>
                <input type="password" name="password" placeholder="Password"
                       class="w-full pl-10 p-2 border rounded-full focus:ring-2 focus:ring-[#4764F3]" required>
            </div>

            <div class="flex items-center justify-between mb-4 text-sm">
                <label class="flex items-center">
                    <input type="checkbox" class="mr-2 text-[#4764F3] focus:ring-[#4764F3]">
                    Remember Me
                </label>
                <a href="#" class="text-[#4764F3] hover:underline">Forgot Password?</a>
            </div>

            <button type="submit"
                    class="w-full bg-[#4764F3] text-white py-2 rounded-full hover:bg-[#3653c7] transition">
                Login
            </button>
        </form>
    </div>

</body>
</html>
