<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Builder HRD / Preview Form Pelamar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #e5e7eb;
        }

        .hover-effect-btn:hover {
            background: #FF6000;
            transition: background .3s ease;
        }

        /* kecilkan scrollbar area preview agar rapi */
        .preview-scroll {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 8px;
        }

        /* Styling untuk sidebar dan dropdown */
        .sidebar {
            width: 250px;
            background-color: #FF6000;
            color: white;
            padding: 1rem;
            height: 100vh;
        }

        .sidebar a,
        .sidebar .dropdown-btn {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .sidebar a:hover,
        .sidebar .dropdown-btn:hover {
            background-color: #ff8640;
        }

        .dropdown-menu {
            display: none;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .dropdown-menu a {
            padding-left: 3rem;
        }

        .active-menu {
            background-color: rgba(255, 243, 176, 0.7);
            color: #000;
            font-weight: bold;
        }

        .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="bg-gray-200 flex">

    {{-- SIDEBAR --}}
    <div class="sidebar flex flex-col items-start fixed">
        <div class="flex items-center mb-6">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-10 w-10 mr-2 rounded-full">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active-menu' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
            Dashboard
        </a>

        {{-- Lamaran --}}
        <div class="w-full">
            <button id="dropdown-btn" class="dropdown-btn w-full text-left focus:outline-none flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm-2 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zM8 12c0 2.21 1.79 4 4 4s4-1.79 4-4-1.79-4-4-4-4 1.79-4 4z" />
                    </svg>
                    Lamaran Pekerjaan
                </div>
                <svg id="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 transform" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="dropdown-menu" class="dropdown-menu">
                <a href="{{ route('admin.jobs.list') }}" class="{{ request()->routeIs('admin.jobs.list') ? 'active-menu' : '' }}">
                    List Job
                </a>
                <a href="{{ route('admin.pelamar.list') }}" class="{{ request()->routeIs('admin.pelamar.list') ? 'active-menu' : '' }}">
                    Data Pelamar
                </a>
                <a href="{{ route('admin.form.lamaran') }}" class="{{ request()->routeIs('admin.form.lamaran') ? 'active-menu' : '' }}">
                    Edit Form Daftar
                </a>
                <a href="{{ route('admin.qrcode') }}" class="{{ request()->routeIs('admin.qrcode') ? 'active-menu' : '' }}">
                    Generate QR
                </a>
            </div>
        </div>

        {{-- 🔹 Dropdown Karyawan (aktif + route) --}}
        <!-- 🔹 Dropdown Karyawan -->
        <div class="w-full">
            <button id="dropdown-karyawan-btn" class="dropdown-btn w-full text-left focus:outline-none flex items-center justify-between">
                <div class="flex items-center">
                    <!-- Ganti SVG sesuai ikon Karyawan -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 
                         3 1.34 3 3 3zm-8 0c1.66 0 
                         2.99-1.34 2.99-3S9.66 5 8 
                         5 5 6.34 5 8s1.34 3 3 3zm0 
                         2c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 
                         14.17 10.33 13 8 13zm8 
                         0c-.29 0-.62.02-.97.05 1.16.84 1.97 
                         1.97 1.97 3.45V20h6v-3.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                    Karyawan
                </div>
                <svg id="dropdown-karyawan-arrow" xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 ml-2 transition-transform duration-300 transform"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 
                   10.586l3.293-3.293a1 1 0 
                   111.414 1.414l-4 4a1 1 0 
                   01-1.414 0l-4-4a1 1 0 
                   010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div id="dropdown-karyawan-menu" class="dropdown-menu">
                <a href="{{ route('karyawan.list') }}" class="{{ request()->routeIs('karyawan.list') ? 'active-menu' : '' }}">
                    Data Karyawan
                </a>
                <a href="#">Pengajuan Cuti</a>
                <a href="#">Pengajuan Izin</a>
                <a href="#">Riwayat Izin & Cuti</a>
            </div>
        </div>


        {{-- 🔹 Dropdown Cuti HRD (masih non aktif) --}}
        <div class="w-full">
            <button class="dropdown-btn w-full text-left flex items-center justify-between disabled">
                <div class="flex items-center">
                    📝 Cuti HRD
                </div>
                <span>▼</span>
            </button>
            <div class="dropdown-menu disabled">
                <a href="#">Pengajuan Izin / Cuti HRD</a>
                <a href="#">Riwayat Izin / Cuti HRD</a>
            </div>
        </div>

        <a href="{{ asset('finger/finger.php') }}" class="{{ request()->is('finger/*') ? 'active-menu' : '' }}">
            Absensi
        </a>

        <div class="mt-auto mb-4">
            <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 rounded-lg hover-highlight">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 
                          01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </a>
        </div>
    </div>

    {{-- MAIN CONTENT WRAPPER UNTUK MENGHINDARI TUMPANG TINDIH --}}
    <div class="flex-1 ml-[250px] main-content p-6">
        @if(session('success'))
        <div class="bg-green-500 text-white p-2 rounded mb-3">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-500 text-white p-2 rounded mb-3">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-500 text-white p-2 rounded mb-3">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h1 class="text-2xl font-bold text-[#072A75] mb-4">Form Builder – Pertanyaan Tambahan HRD</h1>

        {{-- LAYOUT: Builder di atas, Preview form di bawah --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- BUILDER (kiri di desktop) --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-xl font-bold text-[#072A75] mb-4">Form Builder</h2>

                {{-- Pilih Job (satu select mengendalikan preview juga) --}}
                <div class="mb-4">
                    <label for="select-job" class="font-medium block mb-1">Pilih Posisi (Job)</label>
                    <select id="select-job" class="w-full border rounded-lg p-2">
                        <option value="">-- Pilih Posisi --</option>
                        @foreach($jobs as $job)
                        @php
                        $jid = is_array($job) ? ($job['id_job'] ?? '') : ($job->id_job ?? '');
                        $pos = is_array($job) ? ($job['posisi'] ?? '') : ($job->posisi ?? '');
                        @endphp
                        <option value="{{ $jid }}">{{ $pos }}</option>
                        @endforeach
                    </select>
                </div>

                <form id="builder-form" method="POST" action="{{ route('admin.form.builder.save') }}">
                    @csrf
                    <input type="hidden" name="id_job" id="hidden-id-job" />

                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-[#072A75]">Pertanyaan (builder)</h3>
                        <button type="button" id="btn-add" class="bg-green-500 text-white px-3 py-1 rounded-lg">+ Tambah Pertanyaan</button>
                    </div>

                    <div id="fields-container" class="space-y-3"></div>

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-[#072A75] text-white px-6 py-2 rounded-lg shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            {{-- PREVIEW (kanan di desktop) --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg overflow-hidden">
                <h2 class="text-xl font-bold text-[#072A75] mb-4 text-center">Preview — Form Pendaftaran Pelamar (readonly)</h2>
                <div class="preview-scroll">
                    <form class="space-y-4">
                        <div>
                            <label for="preview-nama" class="font-medium">Nama Lengkap</label>
                            <input type="text" id="preview-nama" disabled class="w-full border rounded-lg p-2 bg-gray-100" placeholder="(contoh: Nama Pelamar)">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="preview-tempat-lahir" class="font-medium">Tempat Lahir</label>
                                <input type="text" id="preview-tempat-lahir" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                            </div>
                            <div>
                                <label for="preview-tgl-lahir" class="font-medium">Tanggal Lahir</label>
                                <input type="date" id="preview-tgl-lahir" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                            </div>
                            <div>
                                <label for="preview-umur" class="font-medium">Umur</label>
                                <input type="number" id="preview-umur" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                            </div>
                        </div>

                        <div>
                            <label for="preview-job-select" class="font-medium">Posisi yang Dilamar</label>
                            <select id="preview-job-select" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                                <option value="">-- Pilih Posisi --</option>
                                @foreach($jobs as $job)
                                @php
                                $jid = is_array($job) ? ($job['id_job'] ?? '') : ($job->id_job ?? '');
                                $pos = is_array($job) ? ($job['posisi'] ?? '') : ($job->posisi ?? '');
                                @endphp
                                <option value="{{ $jid }}">{{ $pos }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="preview-nohp" class="font-medium">No. HP</label>
                            <input type="text" id="preview-nohp" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                        </div>

                        <div>
                            <label for="preview-email" class="font-medium">Email</label>
                            <input type="email" id="preview-email" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                        </div>

                        <div>
                            <label for="preview-alamat" class="font-medium">Alamat</label>
                            <textarea id="preview-alamat" disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="preview-pendidikan" class="font-medium">Pendidikan Terakhir</label>
                                <select id="preview-pendidikan" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                                    <option>-- Pilih Pendidikan --</option>
                                    <option>SD/MI</option>
                                    <option>SMP/MTS</option>
                                    <option>SMA/SMK/MA</option>
                                    <option>D3</option>
                                    <option>S1</option>
                                </select>
                            </div>
                            <div>
                                <label for="preview-sekolah" class="font-medium">Sekolah / Universitas</label>
                                <input type="text" id="preview-sekolah" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                            </div>
                        </div>

                        <div>
                            <label for="preview-pengetahuan" class="font-medium">Pengetahuan Perusahaan</label>
                            <textarea id="preview-pengetahuan" disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
                        </div>

                        <div>
                            <label for="preview-gaji" class="font-medium">Ekspektasi Gaji</label>
                            <input type="text" id="preview-gaji" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="preview-kelebihan" class="font-medium">Kelebihan</label>
                                <textarea id="preview-kelebihan" disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
                            </div>
                            <div>
                                <label for="preview-kekurangan" class="font-medium">Kekurangan</label>
                                <textarea id="preview-kekurangan" disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
                            </div>
                        </div>

                        <div>
                            <label for="preview-sosmed" class="font-medium">Sosial Media Aktif</label>
                            <input type="text" id="preview-sosmed" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                        </div>

                        <div>
                            <label for="preview-lokasi" class="font-medium">Bersediakah jika ditempatkan di lokasi manapun?</label>
                            <select id="preview-lokasi" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                                <option>-- Pilih --</option>
                                <option>Ya</option>
                                <option>Tidak</option>
                            </select>
                        </div>

                        <div>
                            <label for="preview-keahlian" class="font-medium">Keahlian</label>
                            <textarea id="preview-keahlian" disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
                        </div>

                        <div>
                            <label for="preview-yakin" class="font-medium">Apakah Anda yakin bisa meyakinkan kami?</label>
                            <textarea id="preview-yakin" disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
                        </div>

                        <div>
                            <label for="preview-kelebihan-kandidat" class="font-medium">Apa Kelebihan Anda dari kandidat lain?</label>
                            <textarea id="preview-kelebihan-kandidat" disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
                        </div>

                        <div>
                            <label for="preview-target" class="font-medium">Apakah Anda siap bekerja di bawah Target? Mengapa?</label>
                            <textarea id="preview-target" disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
                        </div>

                        <div>
                            <label for="preview-mulai-bergabung" class="font-medium">Kapan Anda bisa mulai bergabung dengan tim kami?</label>
                            <input type="text" id="preview-mulai-bergabung" disabled class="w-full border rounded-lg p-2 bg-gray-100">
                        </div>

                        <div>
                            <label for="preview-gaji-alasan" class="font-medium">Mengapa Perusahaan harus memberikan gaji sesuai yang Anda harapkan?</label>
                            <textarea id="preview-gaji-alasan" disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
                        </div>

                        <hr class="my-3">

                        <div id="preview-hrd-fields" class="space-y-3">
                            <h3 class="font-bold text-[#072A75] mb-2">Pertanyaan Tambahan dari HRD</h3>
                        </div>

                        <hr class="my-3">

                        <div>
                            <h3 class="font-bold text-[#072A75]">Pengalaman Kerja (preview)</h3>
                            <div id="preview-pengalaman" class="space-y-3">
                                <div class="border p-3 rounded bg-gray-50">(Kontainer pengalaman — pelamar isi saat apply)</div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TPL untuk builder row --}}
    <template id="tpl-field">
        <div class="border p-4 rounded-lg mb-4 bg-gray-50 field-item" data-field-id="_ID_">
            <div class="flex items-center justify-between mb-2">
                <input type="hidden" class="fld-id" name="fields[_IDX_][id_field]" />
                <input type="hidden" class="fld-urutan" name="fields[_IDX_][urutan]" />
                <input type="hidden" class="fld-namafield" name="fields[_IDX_][nama_field]" />
                <textarea class="border p-2 rounded-lg w-full fld-label" name="fields[_IDX_][label]" placeholder="Tulis pertanyaan"></textarea>
                <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-lg ml-2 btn-remove">Hapus</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="text-sm">Tipe</label>
                    <select class="border p-2 rounded-lg fld-tipe" name="fields[_IDX_][tipe]">
                        <option value="teks">Teks</option>
                        <option value="textarea">Textarea</option>
                        <option value="tanggal">Tanggal</option>
                        <option value="angka">Angka</option>
                        <option value="telepon">Telepon</option>
                        <option value="dropdown">Dropdown</option>
                        <option value="radio">Radio</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm">Opsi (dropdown/radio) — pisahkan baris/koma</label>
                    <textarea class="border p-2 rounded-lg w-full fld-opsi" name="fields[_IDX_][opsi_text]" placeholder="Contoh:\nYa\nTidak"></textarea>
                </div>

                <div class="flex flex-col justify-end gap-2">
                    <label class="inline-flex items-center gap-2">
                        <input type="hidden" name="fields[_IDX_][wajib]" value="0">
                        <input type="checkbox" class="fld-wajib" name="fields[_IDX_][wajib]" value="1"> Wajib
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="hidden" name="fields[_IDX_][tampil]" value="0">
                        <input type="checkbox" class="fld-tampil" name="fields[_IDX_][tampil]" value="1"> Tampilkan ke pelamar
                    </label>
                </div>
            </div>
        </div>
    </template>

    <script>
        // Dropdown Lamaran
        const dropdownBtn = document.getElementById('dropdown-btn');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const dropdownArrow = document.getElementById('dropdown-arrow');
        dropdownBtn.addEventListener('click', () => {
            const isOpen = dropdownMenu.style.display === 'block';
            dropdownMenu.style.display = isOpen ? 'none' : 'block';
            dropdownArrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        });
        // Dropdown Karyawan
        const dropdownKaryawanBtn = document.getElementById('dropdown-karyawan-btn');
        const dropdownKaryawanMenu = document.getElementById('dropdown-karyawan-menu');
        const dropdownKaryawanArrow = document.getElementById('dropdown-karyawan-arrow');

        dropdownKaryawanBtn.addEventListener('click', () => {
            const isOpen = dropdownKaryawanMenu.style.display === 'block';
            dropdownKaryawanMenu.style.display = isOpen ? 'none' : 'block';
            dropdownKaryawanArrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        });


        document.addEventListener('click', (event) => {
            if (!dropdownBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.style.display = 'none';
                dropdownArrow.style.transform = 'rotate(0deg)';
            }
        });

        // Builder + Preview synchronization
        const selectJob = document.getElementById('select-job');
        const hIdJob = document.getElementById('hidden-id-job');
        const fieldsContainer = document.getElementById('fields-container');
        const btnAdd = document.getElementById('btn-add');
        const tplStr = document.getElementById('tpl-field').innerHTML;
        const previewWrap = document.getElementById('preview-hrd-fields');
        const previewJobSelect = document.getElementById('preview-job-select');

        let counter = 0;

        function debounce(fn, wait) {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), wait);
            };
        }

        function createFieldElement(prefill = null) {
            const wrap = document.createElement('div');
            wrap.innerHTML = tplStr.replaceAll('_IDX_', String(counter));
            const el = wrap.firstElementChild;
            el.setAttribute('data-field-id', counter);

            const fldId = el.querySelector('.fld-id');
            const fldUrut = el.querySelector('.fld-urutan');
            const fldName = el.querySelector('.fld-namafield');
            const fldLabel = el.querySelector('.fld-label');
            const fldTipe = el.querySelector('.fld-tipe');
            const fldOpsi = el.querySelector('.fld-opsi');
            const fldWajib = el.querySelector('.fld-wajib');
            const fldTampil = el.querySelector('.fld-tampil');

            if (prefill) {
                if (prefill.id_field) fldId.value = prefill.id_field;
                fldUrut.value = prefill.urutan || fieldsContainer.children.length + 1;
                fldName.value = prefill.nama_field || '';
                fldLabel.value = prefill.label || '';
                fldTipe.value = prefill.tipe || 'teks';
                if (Array.isArray(prefill.opsi)) fldOpsi.value = prefill.opsi.join('\n');
                fldWajib.checked = !!(+prefill.wajib || 0);
                fldTampil.checked = !!(+prefill.tampil || 0);
            } else {
                fldUrut.value = fieldsContainer.children.length + 1;
            }

            if (['dropdown', 'radio'].includes(fldTipe.value)) {
                fldOpsi.parentElement.style.display = '';
            } else {
                fldOpsi.parentElement.style.display = 'none';
            }

            return el;
        }

        function renderPreviewField(f) {
            const container = document.createElement('div');
            container.className = 'flex flex-col gap-1';

            const lab = document.createElement('label');
            // Adding a dynamic `for` attribute for accessibility
            const id = 'preview-dyn-' + Math.random().toString(36).substring(2, 9);
            lab.setAttribute('for', id);
            lab.className = 'font-medium';
            lab.textContent = f.label + (f.wajib ? ' *' : '');
            container.appendChild(lab);

            const tipe = (f.tipe || 'teks').toLowerCase();
            let opsiArr = [];
            if (Array.isArray(f.opsi)) opsiArr = f.opsi;
            else if (typeof f.opsi === 'string' && f.opsi.trim() !== '') {
                opsiArr = f.opsi.split(/\r?\n|,/).map(s => s.trim()).filter(Boolean);
            }

            if (tipe === 'textarea') {
                const ta = document.createElement('textarea');
                ta.disabled = true;
                ta.id = id;
                ta.className = 'w-full border rounded-lg p-2 bg-gray-100';
                container.appendChild(ta);
            } else if (tipe === 'tanggal' || tipe === 'date') {
                const d = document.createElement('input');
                d.type = 'date';
                d.disabled = true;
                d.id = id;
                d.className = 'w-full border rounded-lg p-2 bg-gray-100';
                container.appendChild(d);
            } else if (tipe === 'angka' || tipe === 'number') {
                const n = document.createElement('input');
                n.type = 'number';
                n.disabled = true;
                n.id = id;
                n.className = 'w-full border rounded-lg p-2 bg-gray-100';
                container.appendChild(n);
            } else if (tipe === 'dropdown' || tipe === 'select') {
                const s = document.createElement('select');
                s.disabled = true;
                s.id = id;
                s.className = 'w-full border rounded-lg p-2 bg-gray-100';
                const opt0 = document.createElement('option');
                opt0.textContent = '-- Pilih --';
                s.appendChild(opt0);
                opsiArr.forEach(o => {
                    const op = document.createElement('option');
                    op.textContent = o;
                    s.appendChild(op);
                });
                container.appendChild(s);
            } else if (tipe === 'radio') {
                const wrap = document.createElement('div');
                wrap.className = 'flex gap-3 flex-wrap';
                opsiArr.forEach(o => {
                    const labRadio = document.createElement('label');
                    labRadio.className = 'inline-flex items-center gap-2';
                    const r = document.createElement('input');
                    r.type = 'radio';
                    r.name = 'radio-' + id; // Group radio buttons by name
                    r.disabled = true;
                    labRadio.appendChild(r);
                    labRadio.appendChild(document.createTextNode(' ' + o));
                    wrap.appendChild(labRadio);
                });
                container.appendChild(wrap);
            } else {
                const inp = document.createElement('input');
                inp.type = 'text';
                inp.disabled = true;
                inp.id = id;
                inp.className = 'w-full border rounded-lg p-2 bg-gray-100';
                container.appendChild(inp);
            }

            return container;
        }

        // Event delegation for builder container
        fieldsContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('btn-remove')) {
                const item = event.target.closest('.field-item');
                if (item) {
                    item.remove();
                    syncPreviewFromBuilder();
                }
            }
        });

        fieldsContainer.addEventListener('input', debounce((event) => {
            const target = event.target;
            if (target.classList.contains('fld-label') || target.classList.contains('fld-tipe') || target.classList.contains('fld-opsi')) {
                syncPreviewFromBuilder();
            }
        }, 250));

        fieldsContainer.addEventListener('change', debounce((event) => {
            const target = event.target;
            if (target.classList.contains('fld-tipe') || target.classList.contains('fld-wajib') || target.classList.contains('fld-tampil')) {
                if (target.classList.contains('fld-tipe')) {
                    const item = target.closest('.field-item');
                    const fldOpsi = item.querySelector('.fld-opsi');
                    if (['dropdown', 'radio'].includes(target.value)) {
                        fldOpsi.parentElement.style.display = '';
                    } else {
                        fldOpsi.parentElement.style.display = 'none';
                        fldOpsi.value = '';
                    }
                }
                syncPreviewFromBuilder();
            }
        }, 150));

        btnAdd.addEventListener('click', () => {
            counter++;
            const newField = createFieldElement();
            fieldsContainer.appendChild(newField);
            syncPreviewFromBuilder();
        });

        selectJob.addEventListener('change', async () => {
            const idJob = selectJob.value;
            hIdJob.value = idJob;
            previewJobSelect.value = idJob;
            fieldsContainer.innerHTML = '';
            previewWrap.innerHTML = '';

            if (!idJob) return;

            try {
                const res = await fetch(`http://localhost:8080/api/field-job/byJob/${idJob}`);
                const result = await res.json();
                (result.data || []).forEach(item => {
                    counter++;
                    const newField = createFieldElement(item);
                    fieldsContainer.appendChild(newField);
                });
                syncPreviewFromBuilder();
            } catch (e) {
                console.error(e);
                alert('Gagal memuat pertanyaan untuk job terpilih');
            }
        });

        function syncPreviewFromBuilder() {
            previewWrap.innerHTML = '';
            const rows = Array.from(fieldsContainer.querySelectorAll('.field-item'));
            rows.forEach(row => {
                const label = row.querySelector('.fld-label')?.value || '';
                const tipe = row.querySelector('.fld-tipe')?.value || 'teks';
                const opsiText = row.querySelector('.fld-opsi')?.value || '';
                const wajib = row.querySelector('.fld-wajib')?.checked;
                const tampil = row.querySelector('.fld-tampil')?.checked;

                if (!tampil || !label) return;

                const fieldHtml = renderPreviewField({
                    label,
                    tipe,
                    opsi: opsiText,
                    wajib
                });
                previewWrap.appendChild(fieldHtml);
            });
        }

        document.getElementById('builder-form').addEventListener('submit', () => {
            // Re-order urutan before submit
            [...fieldsContainer.children].forEach((el, idx) => {
                const urut = el.querySelector('.fld-urutan');
                if (urut) urut.value = idx + 1;
            });
        });
    </script>
</body>

</html>