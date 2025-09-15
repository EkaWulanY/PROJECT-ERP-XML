<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XMLTRONIK Karir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="bg-[#072A75] text-white p-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2 rounded-full">
            <span class="text-xl font-bold">XMLTRONIK-KARIR</span>
        </div>
        <div class="flex items-center">
            <span class="mr-2">Pelamar</span>
            <svg class="h-8 w-8 rounded-full border-2 border-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>

    <main class="max-w-6xl mx-auto my-10 bg-white p-8 rounded-xl shadow-md">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2">
                <h1 class="text-3xl font-bold text-gray-800">{{ $job['posisi'] }}</h1>
                <p class="text-gray-800 mt-3">Lokasi : {{ $job['lokasi'] }}</p>
                <p class="text-gray-800 mt-2">Pendidikan Minimal : {{ $job['pendidikan_min'] }}</p>
                <p class="text-gray-800 mt-1">Tanggal Upload : {{ $job['tanggal_post'] }}</p>

                <div class="mt-4 p-4 bg-green-100 border border-green-300 rounded-lg text-green-800">
                    @if(isset($job['show_gaji']) && $job['show_gaji'] == 1 && isset($job['range_gaji']))
                    <p class="font-bold text-lg">Range Gaji: {{ $job['range_gaji'] }}</p>
                    @else
                    <p class="font-bold text-lg">Gaji tidak ditampilkan</p>
                    @endif
                </div>

                <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                    <h2 class="text-xl font-bold mb-2">KUALIFIKASI :</h2>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach(explode("\n", str_replace("\\n", "\n", $job['kualifikasi'])) as $item)
                        @if(trim($item) != '')
                        <li>{{ $item }}</li>
                        @endif
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                    <h2 class="text-xl font-bold mb-2">JOBDESK :</h2>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach(explode("\n", str_replace("\\n", "\n", $job['jobdesk'])) as $item)
                        @if(trim($item) != '')
                        <li>{{ $item }}</li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="flex">
                @if($job['image_url'])
                <img src="{{ $job['image_url'] }}" alt="Poster Lowongan"
                    class="rounded-lg shadow-md w-full h-full object-contain cursor-pointer hover:scale-105 transition-transform duration-300"
                    onclick="openModal('{{ $job['image_url'] }}')">
                @else
                <div class="bg-gray-300 h-64 flex items-center justify-center rounded-lg w-full">No Image</div>
                @endif
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('pelamar.jobs') }}"
                class="bg-gray-400 text-white px-6 py-3 rounded-lg font-semibold">
                Lamar Nanti
            </a>
            <a href="{{ route('pelamar.create', ['id_job' => $job['id_job']]) }}"
                class="bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold">
                Lamar Sekarang
            </a>
        </div>
    </main>

    <div id="imageModal"
        class="fixed inset-0 hidden bg-black bg-opacity-75 flex items-center justify-center z-50">
        <span class="absolute top-5 right-7 text-white text-3xl cursor-pointer" onclick="closeModal()">&times;</span>
        <img id="modalImage" src="" alt="Poster" class="max-w-3xl max-h-[90vh] rounded-lg shadow-lg">
    </div>

    <script>
        function openModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }
    </script>
</body>

</html>