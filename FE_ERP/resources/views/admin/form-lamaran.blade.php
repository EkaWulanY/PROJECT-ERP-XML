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
      background: #4E71FF;
      transition: background .3s ease;
    }

    /* kecilkan scrollbar area preview agar rapi */
    .preview-scroll {
      max-height: 70vh;
      overflow-y: auto;
      padding-right: 8px;
    }
  </style>
</head>

<body class="bg-gray-200">

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

  {{-- HEADER --}}
  <div class="bg-[#072A75] text-white p-4 flex justify-between items-center shadow-lg">
    <div class="flex items-center">
      <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2 rounded-full">
      <span class="text-xl font-bold">Sistem ERP HR</span>
    </div>
    <div class="flex items-center">
      <span class="mr-2">Admin</span>
      <svg class="h-8 w-8 rounded-full border-2 border-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
    </div>
  </div>

  {{-- NAVBAR --}}
  <div class="container mx-auto p-8">
    <div class="flex justify-center space-x-4 mb-8">
      <a href="{{ route('admin.jobs.list') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">List Job</a>
      <a href="{{ route('admin.pelamar.list') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Data Pelamar</a>
      <a href="{{ route('admin.qrcode') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Generate QR</a>
      <a href="{{ route('admin.form.lamaran') }}" class="bg-purple-500 text-white font-semibold py-2 px-6 rounded-lg shadow-lg">Edit Form Daftar</a>
      <a href="{{ asset('finger/finger.php') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Absensi</a>
    </div>

    {{-- LAYOUT: Builder di atas, Preview form di bawah --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      {{-- BUILDER (kiri di desktop) --}}
      <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h2 class="text-xl font-bold text-[#072A75] mb-4">Form Builder – Pertanyaan Tambahan HRD</h2>

        {{-- Pilih Job (satu select mengendalikan preview juga) --}}
        <div class="mb-4">
          <label class="font-medium block mb-1">Pilih Posisi (Job)</label>
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
          {{-- Kita reuse struktur form pelamar tapi semua input disabled --}}
          <form class="space-y-4">
            {{-- Nama Lengkap --}}
            <div>
              <label class="font-medium">Nama Lengkap</label>
              <input type="text" disabled class="w-full border rounded-lg p-2 bg-gray-100" placeholder="(contoh: Nama Pelamar)">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="font-medium">Tempat Lahir</label>
                <input type="text" disabled class="w-full border rounded-lg p-2 bg-gray-100">
              </div>
              <div>
                <label class="font-medium">Tanggal Lahir</label>
                <input type="date" disabled class="w-full border rounded-lg p-2 bg-gray-100">
              </div>
              <div>
                <label class="font-medium">Umur</label>
                <input type="number" disabled class="w-full border rounded-lg p-2 bg-gray-100">
              </div>
            </div>

            <div>
              <label class="font-medium">Posisi yang Dilamar</label>
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
              <label class="font-medium">No. HP</label>
              <input type="text" disabled class="w-full border rounded-lg p-2 bg-gray-100">
            </div>

            <div>
              <label class="font-medium">Email</label>
              <input type="email" disabled class="w-full border rounded-lg p-2 bg-gray-100">
            </div>

            <div>
              <label class="font-medium">Alamat</label>
              <textarea disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="font-medium">Pendidikan Terakhir</label>
                <select disabled class="w-full border rounded-lg p-2 bg-gray-100">
                  <option>-- Pilih Pendidikan --</option>
                  <option>SD/MI</option>
                  <option>SMP/MTS</option>
                  <option>SMA/SMK/MA</option>
                  <option>D3</option>
                  <option>S1</option>
                </select>
              </div>
              <div>
                <label class="font-medium">Sekolah / Universitas</label>
                <input type="text" disabled class="w-full border rounded-lg p-2 bg-gray-100">
              </div>
            </div>

            <div>
              <label class="font-medium">Pengetahuan Perusahaan</label>
              <textarea disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
            </div>

            <div>
              <label class="font-medium">Ekspektasi Gaji</label>
              <input type="text" disabled class="w-full border rounded-lg p-2 bg-gray-100">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="font-medium">Kelebihan</label>
                <textarea disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
              </div>
              <div>
                <label class="font-medium">Kekurangan</label>
                <textarea disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
              </div>
            </div>

            <div>
              <label class="font-medium">Sosial Media Aktif</label>
              <input type="text" disabled class="w-full border rounded-lg p-2 bg-gray-100">
            </div>

            <div>
              <label class="font-medium">Bersediakah jika ditempatkan di lokasi manapun?</label>
              <select disabled class="w-full border rounded-lg p-2 bg-gray-100">
                <option>-- Pilih --</option>
                <option>Ya</option>
                <option>Tidak</option>
              </select>
            </div>

            <div>
              <label class="font-medium">Keahlian</label>
              <textarea disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
            </div>

            <div>
              <label class="font-medium">Apakah Anda yakin bisa meyakinkan kami?</label>
              <textarea disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
            </div>

            <div>
              <label class="font-medium">Apa Kelebihan Anda dari kandidat lain?</label>
              <textarea disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
            </div>

            <div>
              <label class="font-medium">Apakah Anda siap bekerja di bawah Target? Mengapa?</label>
              <textarea disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
            </div>

            <div>
              <label class="font-medium">Kapan Anda bisa mulai bergabung dengan tim kami?</label>
              <input type="text" disabled class="w-full border rounded-lg p-2 bg-gray-100">
            </div>

            <div>
              <label class="font-medium">Mengapa Perusahaan harus memberikan gaji sesuai yang Anda harapkan?</label>
              <textarea disabled class="w-full border rounded-lg p-2 bg-gray-100"></textarea>
            </div>

            <hr class="my-3">

            {{-- Area: Pertanyaan Tambahan (dinamis) --}}
            <div>
              <h3 class="font-bold text-[#072A75] mb-2">Pertanyaan Tambahan dari HRD</h3>
              <div id="preview-hrd-fields" class="space-y-3"></div>
            </div>

            <hr class="my-3">

            {{-- Pengalaman kerja (preview) --}}
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
    <div class="border p-4 rounded-lg mb-4 bg-gray-50 field-item">
      <div class="flex items-center justify-between mb-2">
        <input type="hidden" class="fld-id" name="fields[_IDX_][id_field]" />
        <input type="hidden" class="fld-urutan" name="fields[_IDX_][urutan]" />
        <input type="hidden" class="fld-namafield" name="fields[_IDX_][nama_field]" />
        <input type="text" class="border p-2 rounded-lg w-full fld-label" name="fields[_IDX_][label]" placeholder="Tulis pertanyaan" />
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
            <!-- hidden default 0 -->
            <input type="hidden" name="fields[_IDX_][wajib]" value="0">
            <input type="checkbox" class="fld-wajib" name="fields[_IDX_][wajib]" value="1"> Wajib
          </label>
          <label class="inline-flex items-center gap-2">
            <!-- hidden default 0 -->
            <input type="hidden" name="fields[_IDX_][tampil]" value="0">
            <input type="checkbox" class="fld-tampil" name="fields[_IDX_][tampil]" value="1"> Tampilkan ke pelamar
          </label>
        </div>
      </div>
    </div>
  </template>

  <script>
    // Builder + Preview syncronization
    const selectJob = document.getElementById('select-job');
    const hIdJob = document.getElementById('hidden-id-job');
    const fieldsContainer = document.getElementById('fields-container');
    const btnAdd = document.getElementById('btn-add');
    const tplStr = document.getElementById('tpl-field').innerHTML;
    const previewWrap = document.getElementById('preview-hrd-fields');
    const previewJobSelect = document.getElementById('preview-job-select');
    let counter = 0;

    function rowHTML() {
      counter += 1;
      return tplStr.replaceAll('_IDX_', String(counter));
    }

    function addRow(prefill = null) {
      const wrap = document.createElement('div');
      wrap.innerHTML = rowHTML();
      const el = wrap.firstElementChild;

      const fldId = el.querySelector('.fld-id');
      const fldUrut = el.querySelector('.fld-urutan');
      const fldName = el.querySelector('.fld-namafield');
      const fldLabel = el.querySelector('.fld-label');
      const fldTipe = el.querySelector('.fld-tipe');
      const fldOpsi = el.querySelector('.fld-opsi');
      const fldWajib = el.querySelector('.fld-wajib');
      const fldTampil = el.querySelector('.fld-tampil');
      const btnRemove = el.querySelector('.btn-remove');

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

      function toggleOpsi() {
        if (['dropdown', 'radio'].includes(fldTipe.value)) {
          fldOpsi.parentElement.style.display = '';
        } else {
          fldOpsi.parentElement.style.display = 'none';
          fldOpsi.value = '';
        }
      }
      toggleOpsi();
      fldTipe.addEventListener('change', toggleOpsi);

      btnRemove.addEventListener('click', () => {
        el.remove();
        syncPreviewFromBuilder();
      });

      // update preview on label/type change
      fldLabel.addEventListener('input', debounce(syncPreviewFromBuilder, 250));
      fldTipe.addEventListener('change', debounce(syncPreviewFromBuilder, 150));
      fldOpsi.addEventListener('input', debounce(syncPreviewFromBuilder, 250));
      fldWajib.addEventListener('change', debounce(syncPreviewFromBuilder, 150));
      fldTampil.addEventListener('change', debounce(syncPreviewFromBuilder, 150));

      fieldsContainer.appendChild(el);
      syncPreviewFromBuilder();
    }

    btnAdd.addEventListener('click', () => addRow());

    // load existing fields for job via API
    selectJob.addEventListener('change', async () => {
      const idJob = selectJob.value;
      hIdJob.value = idJob;
      previewJobSelect.value = idJob;
      fieldsContainer.innerHTML = '';
      previewWrap.innerHTML = '';
      if (!idJob) return;
      try {
        const res = await fetch(`http://localhost:8080/api/field-job/byJob/${idJob}`);
        const result = await res.json(); // Renamed to avoid confusion

        // The array you need is inside the 'data' property of the result object
        (result.data || []).forEach(item => addRow(item));
        // preview also built inside addRow -> syncPreviewFromBuilder
      } catch (e) {
        console.error(e);
        alert('Gagal memuat pertanyaan untuk job terpilih');
      }
    });

    // Sync preview area from builder content
    function syncPreviewFromBuilder() {
      previewWrap.innerHTML = '';
      const rows = Array.from(fieldsContainer.querySelectorAll('.field-item'));
      rows.forEach(row => {
        const label = row.querySelector('.fld-label')?.value || '';
        const tipe = row.querySelector('.fld-tipe')?.value || 'teks';
        const opsiText = row.querySelector('.fld-opsi')?.value || '';
        const wajib = row.querySelector('.fld-wajib')?.checked ? 1 : 0;
        const tampil = row.querySelector('.fld-tampil')?.checked ? 1 : 0;

        if (!tampil) return; // jika tidak tampil ke pelamar, jangan preview

        const fieldHtml = renderPreviewField({
          label,
          tipe,
          opsi: opsiText,
          wajib
        });
        previewWrap.appendChild(fieldHtml);
      });
    }

    function renderPreviewField(f) {
      // f: { label, tipe, opsi (string or array), wajib }
      const container = document.createElement('div');
      container.className = 'flex flex-col gap-1';

      const lab = document.createElement('label');
      lab.className = 'font-medium';
      lab.textContent = f.label + (f.wajib ? ' *' : '');
      container.appendChild(lab);

      const tipe = (f.tipe || 'teks').toLowerCase();
      // opsi may be array or newline/csv string
      let opsiArr = [];
      if (Array.isArray(f.opsi)) opsiArr = f.opsi;
      else if (typeof f.opsi === 'string' && f.opsi.trim() !== '') {
        opsiArr = f.opsi.split(/\r?\n|,/).map(s => s.trim()).filter(Boolean);
      }

      if (tipe === 'textarea') {
        const ta = document.createElement('textarea');
        ta.disabled = true;
        ta.className = 'w-full border rounded-lg p-2 bg-gray-100';
        container.appendChild(ta);
      } else if (tipe === 'tanggal' || tipe === 'date') {
        const d = document.createElement('input');
        d.type = 'date';
        d.disabled = true;
        d.className = 'w-full border rounded-lg p-2 bg-gray-100';
        container.appendChild(d);
      } else if (tipe === 'angka' || tipe === 'number') {
        const n = document.createElement('input');
        n.type = 'number';
        n.disabled = true;
        n.className = 'w-full border rounded-lg p-2 bg-gray-100';
        container.appendChild(n);
      } else if (tipe === 'dropdown' || tipe === 'select') {
        const s = document.createElement('select');
        s.disabled = true;
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
          r.disabled = true;
          labRadio.appendChild(r);
          labRadio.appendChild(document.createTextNode(' ' + o));
          wrap.appendChild(labRadio);
        });
        container.appendChild(wrap);
      } else {
        // default teks
        const inp = document.createElement('input');
        inp.type = 'text';
        inp.disabled = true;
        inp.className = 'w-full border rounded-lg p-2 bg-gray-100';
        container.appendChild(inp);
      }

      return container;
    }

    // debounce helper
    function debounce(fn, wait) {
      let t;
      return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), wait);
      };
    }

    // set urutan before submit
    document.getElementById('builder-form').addEventListener('submit', () => {
      [...fieldsContainer.children].forEach((el, idx) => {
        const urut = el.querySelector('.fld-urutan');
        if (urut) urut.value = idx + 1;
      });
    });

    // on initial load: nothing selected. (You can pre-select a job by setting selectJob.value)
  </script>
</body>

</html>