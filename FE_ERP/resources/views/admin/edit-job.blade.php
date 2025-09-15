<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        #customGajiWrapper {
            display: none;
        }
        #previewImage {
            margin-top: 10px;
            max-height: 200px;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Edit Lowongan</h3>
        </div>

        <div class="card-body bg-light">
            {{-- Alert error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali input Anda:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Alert success --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.jobs.update', $job['id_job']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Posisi</label>
                        <input type="text" name="posisi" class="form-control"
                               value="{{ old('posisi', $job['posisi']) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control"
                               value="{{ old('lokasi', $job['lokasi']) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $job['deskripsi']) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jobdesk</label>
                    <textarea name="jobdesk" class="form-control" rows="3" required>{{ old('jobdesk', $job['jobdesk']) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kualifikasi</label>
                    <textarea name="kualifikasi" class="form-control" rows="3" required>{{ old('kualifikasi', $job['kualifikasi']) }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Pendidikan Minimal</label>
                        <input type="text" name="pendidikan_min" class="form-control"
                               value="{{ old('pendidikan_min', $job['pendidikan_min']) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Range Gaji</label>
                        <select name="range_gaji" id="range_gaji" class="form-select">
                            <option value="">-- Pilih Range Gaji --</option>
                            <option value="Rp 1.000.000 - Rp 2.500.000" {{ old('range_gaji', $job['range_gaji']) == 'Rp 1.000.000 - Rp 2.500.000' ? 'selected' : '' }}>Rp 1.000.000 - Rp 2.500.000</option>
                            <option value="Rp 3.000.000 - Rp 5.500.000" {{ old('range_gaji', $job['range_gaji']) == 'Rp 3.000.000 - Rp 5.500.000' ? 'selected' : '' }}>Rp 3.000.000 - Rp 5.500.000</option>
                            <option value="Rp 6.000.000 - Rp 8.500.000" {{ old('range_gaji', $job['range_gaji']) == 'Rp 6.000.000 - Rp 8.500.000' ? 'selected' : '' }}>Rp 6.000.000 - Rp 8.500.000</option>
                            <option value="Rp 9.000.000 - Rp 10.500.000" {{ old('range_gaji', $job['range_gaji']) == 'Rp 9.000.000 - Rp 10.500.000' ? 'selected' : '' }}>Rp 9.000.000 - Rp 10.500.000</option>
                            <option value="custom" {{ !in_array($job['range_gaji'], [
                                'Rp 1.000.000 - Rp 2.500.000',
                                'Rp 3.000.000 - Rp 5.500.000',
                                'Rp 6.000.000 - Rp 8.500.000',
                                'Rp 9.000.000 - Rp 10.500.000'
                            ]) ? 'selected' : '' }}>Custom</option>
                        </select>
                        <div id="customGajiWrapper" class="mt-2">
                            <input type="text" name="custom_gaji" class="form-control" placeholder="Masukkan range gaji manual"
                                   value="{{ !in_array($job['range_gaji'], [
                                        'Rp 1.000.000 - Rp 2.500.000',
                                        'Rp 3.000.000 - Rp 5.500.000',
                                        'Rp 6.000.000 - Rp 8.500.000',
                                        'Rp 9.000.000 - Rp 10.500.000'
                                   ]) ? $job['range_gaji'] : old('custom_gaji') }}">
                        </div>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="show_gaji" id="show_gaji" class="form-check-input" value="1"
                                   {{ old('show_gaji', $job['show_gaji']) ? 'checked' : '' }}>
                            <label for="show_gaji" class="form-check-label">Tampilkan gaji ke publik</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Batas Lamaran</label>
                        <input type="date" name="batas_lamaran" class="form-control"
                               value="{{ old('batas_lamaran', $job['batas_lamaran']) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Post <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_post" class="form-control"
                               value="{{ old('tanggal_post', $job['tanggal_post']) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif" {{ old('status', $job['status']) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $job['status']) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload Gambar</label>
                        <input type="file" name="image_url" id="image_url" class="form-control" accept="image/*">
                        @if($job['image_url'])
                            <img id="previewImage" src="{{ asset('storage/' . $job['image_url']) }}" alt="Preview" class="d-block mt-2">
                        @else
                            <img id="previewImage" src="#" alt="Preview" style="display:none;">
                        @endif
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('admin.jobs.list') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle input custom gaji
    const rangeGaji = document.getElementById('range_gaji');
    const customWrapper = document.getElementById('customGajiWrapper');
    function toggleCustomGaji() {
        customWrapper.style.display = rangeGaji.value === 'custom' ? 'block' : 'none';
    }
    rangeGaji.addEventListener('change', toggleCustomGaji);
    toggleCustomGaji();

    // Preview image sebelum upload
    const imageInput = document.getElementById('image_url');
    const previewImage = document.getElementById('previewImage');
    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            previewImage.src = URL.createObjectURL(file);
            previewImage.style.display = 'block';
        }
    });
</script>
</body>
</html>
