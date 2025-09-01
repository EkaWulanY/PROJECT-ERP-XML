<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Form + QR Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-[#072A75] mb-4 text-center">Hasil Perubahan Form Lamaran</h2>

        {{-- Scrollable container --}}
        <div class="max-h-[500px] overflow-y-auto border rounded-xl p-4 mb-6">
            <form class="space-y-4">
                {{-- Contoh render pertanyaan tambahan --}}
                @if(isset($pertanyaan) && count($pertanyaan) > 0)
                    @foreach($pertanyaan as $p)
                        <div class="mb-4">
                            <label class="block font-semibold mb-2">{{ $p['label'] }}</label>

                            @if($p['tipe'] === 'text')
                                <input type="text" class="w-full border rounded-lg p-2" placeholder="Jawaban...">

                            @elseif($p['tipe'] === 'textarea')
                                <textarea class="w-full border rounded-lg p-2" rows="3" placeholder="Jawaban..."></textarea>

                            @elseif($p['tipe'] === 'radio')
                                <div class="flex flex-col gap-2">
                                    {{-- Default contoh opsi radio --}}
                                    <label class="flex items-center">
                                        <input type="radio" name="radio_{{ $loop->index }}" class="mr-2"> Opsi 1
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="radio_{{ $loop->index }}" class="mr-2"> Opsi 2
                                    </label>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">Belum ada pertanyaan tambahan.</p>
                @endif

                {{-- Pengalaman kerja (contoh render) --}}
                @if(isset($pengalaman) && count($pengalaman) > 0)
                    <h3 class="text-lg font-bold text-[#072A75] mt-6">Pengalaman Kerja</h3>
                    @foreach($pengalaman as $exp)
                        <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                            <p><strong>Perusahaan:</strong> {{ $exp['nama_perusahaan'] ?? '-' }}</p>
                            <p><strong>Posisi:</strong> {{ $exp['posisi'] ?? '-' }}</p>
                            <p><strong>Tahun:</strong> {{ $exp['tahun_mulai'] ?? '' }} - {{ $exp['tahun_selesai'] ?? '' }}</p>
                            <p><strong>Deskripsi:</strong> {{ $exp['pengalaman'] ?? '-' }}</p>
                            <p><strong>Alasan Resign:</strong> {{ $exp['alasan_resign'] ?? '-' }}</p>
                        </div>
                    @endforeach
                @endif
            </form>
        </div>

        {{-- QR Code --}}
        <div class="flex flex-col items-center">
            <h3 class="text-lg font-bold text-[#072A75] mb-2">QR Code</h3>
            <div class="p-4 border rounded-xl shadow">
                {{-- QR Code dengan simple-qrcode --}}
                {!! QrCode::size(150)->generate(url('/form/lamaran')) !!}
            </div>
            <p class="text-gray-600 text-sm mt-2">Scan QR untuk membuka form ini.</p>
        </div>
    </div>

</body>
</html>
