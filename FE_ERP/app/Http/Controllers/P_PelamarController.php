<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\P_Job; // Pastikan model ini tersedia
use App\Models\P_FormLamaran;
use App\Models\P_PengalamanKerja;
use App\Models\FormField;
use App\Models\P_JawabanPelamar;
use Illuminate\Support\Facades\Log; // Gunakan ini untuk debugging jika diperlukan

class P_PelamarController extends Controller
{
    /**
     * Menampilkan halaman utama untuk pelamar dan memuat dropdown posisi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $jobs = Job::select('posisi')->get()->unique('posisi');
        return view('p_pelamar_view', compact('jobs'));
    }

    public function showJob($id)
    {
        $job = Job::findOrFail($id);
        return view('p_job_detail', compact('job')); // sesuai nama file
    }

    /**
     * Mengambil daftar lowongan kerja yang berstatus 'aktif'.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAktifJobs()
    {
        $jobs = Job::where('status', 'aktif')->orderBy('tanggal_post', 'desc')->get();
        return response()->json($jobs);
    }

    /**
     * Menampilkan form lamaran.
     */
    public function create(Request $request)
    {
        // Ambil ID job dari URL, jika ada. Menggunakan query() untuk parameter URL.
        $selectedJobId = $request->query('id_job');
        $selectedJob = null;
        $jobs = null;

        // Ambil pertanyaan dinamis hanya jika ada ID job yang valid.
        $dynamicFields = collect();
        if ($selectedJobId) {
            // Ambil data job berdasarkan id untuk ditampilkan di form
            $selectedJob = P_Job::find($selectedJobId);

            // Jika job tidak ditemukan, berikan pesan error atau redirect
            if (!$selectedJob) {
                return redirect()->route('p_pelamar_view')->with('error', 'Posisi yang Anda pilih tidak ditemukan.');
            }

            $baseFields = [
                'nama_lengkap',
                'tempat_lahir',
                'tanggal_lahir',
                'umur',
                'alamat',
                'no_hp',
                'email',
                'pendidikan_terakhir',
                'nama_sekolah',
                'jurusan',
                'pengetahuan_perusahaan',
                'bersedia_cilacap',
                'keahlian',
                'tujuan_daftar',
                'kelebihan',
                'kekurangan',
                'sosmed_aktif',
                'alasan_merekrut',
                'kelebihan_dari_yang_lain',
                'alasan_bekerja_dibawah_tekanan',
                'kapan_bisa_gabung',
                'ekspektasi_gaji',
                'alasan_ekspektasi',
                'upload_berkas',
                'posisi_pekerjaan'
            ];

            $dynamicFields = FormField::where('id_job', $selectedJobId)
                ->where('tampil', 1)
                ->orderBy('urutan')
                ->get()
                ->reject(function ($f) use ($baseFields) {
                    return in_array($f->nama_field, $baseFields);
                });
        }

        // Kirim semua data yang diperlukan ke view.
        return view('pelamar.form', compact('selectedJobId', 'selectedJob', 'dynamicFields', 'jobs'));
    }

    /**
     * Simpan data lamaran & pengalaman kerja.
     */
    public function store(Request $request)
    {
        // === PERBAIKAN: Mengubah nilai bersedia_cilacap agar sesuai dengan ENUM ===
        $bersediaCilacap = $request->bersedia_cilacap;
        if ($bersediaCilacap === 'tidak') {
            $bersediaCilacap = 'tidak bersedia';
        }

        // Simpan data lamaran utama
        $lamaran = P_FormLamaran::create([
            'id_job' => $request->id_job,
            'nama_lengkap' => $request->nama_lengkap,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'umur' => $request->umur,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'nama_sekolah' => $request->nama_sekolah,
            'jurusan' => $request->jurusan,
            'pengetahuan_perusahaan' => $request->pengetahuan_perusahaan,
            'kelebihan' => $request->kelebihan,
            'kekurangan' => $request->kekurangan,
            'sosmed_aktif' => $request->sosmed_aktif,
            'ekspektasi_gaji' => $request->ekspektasi_gaji,
            'bersedia_cilacap' => $bersediaCilacap, // Perbaikan: Gunakan variabel yang sudah diperbaiki
            'keahlian' => $request->keahlian,
            'alasan_merekrut' => $request->alasan_merekrut,
            'kelebihan_dari_yang_lain' => $request->kelebihan_dari_yang_lain,
            'alasan_bekerja_dibawah_tekanan' => $request->alasan_bekerja_dibawah_tekanan,
            'kapan_bisa_gabung' => $request->kapan_bisa_gabung,
            'alasan_ekspektasi' => $request->alasan_ekspektasi,
        ]);

        if ($request->hasFile('upload_berkas')) {
            $files = [];
            foreach ($request->file('upload_berkas') as $file) {
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('berkas', $namaFile, 'public');
                $files[] = $namaFile;
            }
            $lamaran->upload_berkas = json_encode($files);
            $lamaran->save();
        }

        // 🔹 Simpan pengalaman kerja (jika ada)
        if ($request->has('pengalaman')) {
            foreach ($request->pengalaman as $exp) {
                // ✅ PERBAIKAN: Mengganti 'pengalaman' menjadi 'pengalaman_kerja'
                P_PengalamanKerja::create([
                    'id_lamaran' => $lamaran->id_lamaran,
                    'nama_perusahaan' => $exp['nama_perusahaan'],
                    'tahun_mulai' => $exp['tahun_mulai'],
                    'tahun_selesai' => $exp['tahun_selesai'],
                    'posisi' => $exp['posisi'],
                    'pengalaman' => $exp['pengalaman'], // ✅ PERBAIKAN KODE
                    'alasan_resign' => $exp['alasan_resign'],
                ]);
            }
        }
        // ✅ KODE TAMBAHAN UNTUK MENYIMPAN JAWABAN DINAMIS
        if ($request->has('field')) {
            foreach ($request->field as $id_field => $jawaban) {
                P_JawabanPelamar::create([
                    'id_lamaran' => $lamaran->id_lamaran,
                    'id_field' => $id_field,
                    'jawaban' => $jawaban,
                ]);
            }
        }

        // Log::info('Jawaban tambahan telah disimpan.'); // Ini bisa diaktifkan untuk debugging
        return redirect()->route('pelamar.show', $lamaran->id_lamaran);
    }

    /**
     * Menampilkan detail lamaran (readonly).
     */
    public function show($id)
    {
        $pelamar = P_FormLamaran::with('job')->findOrFail($id);
        return view('pelamar.detail', compact('pelamar'));
    }

    /**
     * Menampilkan halaman verifikasi (kirim ke WA).
     */
    public function verifikasi($id)
    {
        $pelamar = P_FormLamaran::with('job')->findOrFail($id);
        return view('pelamar.verifikasi', compact('pelamar'));
    }
}
