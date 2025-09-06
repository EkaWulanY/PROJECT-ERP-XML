<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\FormField;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api_backend.base_url', 'http://localhost:8080');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /* ==========================================================
     * JOBS MANAGEMENT
     * ========================================================== */
    public function listJobs()
    {
        try {
            $jobs = Http::get("{$this->baseUrl}/api/jobs")->throw()->json();
            $jobs = is_array($jobs) ? $jobs : [];
        } catch (\Exception $e) {
            Log::error('Error fetching jobs: ' . $e->getMessage());
            $jobs = [];
        }
        return view('admin.list-jobs', compact('jobs'));
    }

    public function showJobForm()
    {
        return view('admin.create-job');
    }

    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'posisi'         => 'required|string|max:255',
            'deskripsi'      => 'required|string',
            'jobdesk'        => 'required|string',
            'kualifikasi'    => 'required|string',
            'lokasi'         => 'required|string|max:255',
            'status'         => 'nullable|string|in:aktif,nonaktif',
            'tanggal_post'   => 'required|date',
            'pendidikan_min' => 'nullable|string|max:255',
            'batas_lamaran'  => 'nullable|date',
            'image_url'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $payload = $validated;
        $payload['status'] = $validated['status'] ?? 'aktif';

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('jobs', 'public');
            $payload['image_url'] = asset('storage/' . $path);
        }

        try {
            Http::post("{$this->baseUrl}/api/jobs", $payload)->throw();
            return redirect()->route('admin.jobs.list')->with('success', 'Job berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error storing job: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambahkan job.');
        }
    }
    /* ==========================================================
 * JOBS MANAGEMENT (LANJUTAN)
 * ========================================================== */
    public function editJob($id)
    {
        try {
            $job = Http::get("{$this->baseUrl}/api/jobs/{$id}")->throw()->json();
            return view('admin.edit-job', compact('job'));
        } catch (\Exception $e) {
            Log::error("Error fetching job {$id}: " . $e->getMessage());
            return redirect()->route('admin.jobs.list')->with('error', 'Gagal mengambil data job.');
        }
    }

    public function updateJob(Request $request, $id)
    {
        $validated = $request->validate([
            'posisi'         => 'required|string|max:255',
            'deskripsi'      => 'required|string',
            'jobdesk'        => 'required|string',
            'kualifikasi'    => 'required|string',
            'lokasi'         => 'required|string|max:255',
            'status'         => 'nullable|string|in:aktif,nonaktif',
            'tanggal_post'   => 'required|date',
            'pendidikan_min' => 'nullable|string|max:255',
            'batas_lamaran'  => 'nullable|date',
        ]);

        try {
            Http::put("{$this->baseUrl}/api/jobs/{$id}", $validated)->throw();
            return redirect()->route('admin.jobs.list')->with('success', 'Job berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error updating job {$id}: " . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui job.');
        }
    }

    public function deleteJob($id)
    {
        try {
            Http::delete("{$this->baseUrl}/api/jobs/{$id}")->throw();
            return redirect()->route('admin.jobs.list')->with('success', 'Job berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting job {$id}: " . $e->getMessage());
            return redirect()->route('admin.jobs.list')->with('error', 'Gagal menghapus job.');
        }
    }

   public function activateJob($id)
{
    try {
        Http::withBody(json_encode(['status' => 'aktif']), 'application/json')
            ->put("{$this->baseUrl}/api/jobs/{$id}")
            ->throw();

        return redirect()->route('admin.jobs.list')->with('success', 'Job berhasil diaktifkan.');
    } catch (\Exception $e) {
        Log::error("Error activating job {$id}: " . $e->getMessage());
        return redirect()->route('admin.jobs.list')->with('error', 'Gagal mengaktifkan job.');
    }
}

public function deactivateJob($id)
{
    try {
        Http::withBody(json_encode(['status' => 'nonaktif']), 'application/json')
            ->put("{$this->baseUrl}/api/jobs/{$id}")
            ->throw();

        return redirect()->route('admin.jobs.list')->with('success', 'Job berhasil dinonaktifkan.');
    } catch (\Exception $e) {
        Log::error("Error deactivating job {$id}: " . $e->getMessage());
        return redirect()->route('admin.jobs.list')->with('error', 'Gagal menonaktifkan job.');
    }
}


    /* ==========================================================
     * PELAMAR MANAGEMENT
     * ========================================================== */
    public function listPelamar()
    {
        $rank = [
            'SD' => 1,
            'SMP' => 2,
            'SMA' => 3,
            'SMK' => 3,
            'MA' => 3,
            'D1' => 4,
            'D2' => 5,
            'D3' => 6,
            'S1' => 7,
            'S2' => 8,
            'S3' => 9,
        ];

        try {
            $pelamar = Http::get("{$this->baseUrl}/api/pelamar")->throw()->json();
            $jobs = Http::get("{$this->baseUrl}/api/jobs")->throw()->json();

            $pelamar = is_array($pelamar) ? $pelamar : [];
            $jobs = is_array($jobs) ? $jobs : [];
            $jobMap = collect($jobs)->keyBy('id_job')->all();

            foreach ($pelamar as $key => $p) {
                $idJob = $p['id_job'] ?? null;
                $job = $idJob && isset($jobMap[$idJob]) ? $jobMap[$idJob] : null;
                $pelamar[$key]['posisi_dilamar'] = $job['posisi'] ?? 'N/A';

                if ($job) {
                    $minEdu = $rank[$job['pendidikan_min']] ?? 0;
                    $appEdu = $rank[$p['pendidikan_terakhir']] ?? 0;
                    if ($appEdu < $minEdu) {
                        $pelamar[$key]['status'] = 'belum_sesuai';
                    }
                }
            }

            $groupedPelamar = [
                'on_progress' => [],
                'pending'     => [],
                'talent_pool' => [],
                'lolos'       => [],
                'tidak_lolos' => [],
            ];
            foreach ($pelamar as $p) {
                switch ($p['status'] ?? 'proses') {
                    case 'belum_sesuai':
                        $groupedPelamar['pending'][] = $p;
                        break;
                    case 'talent_pool':
                        $groupedPelamar['talent_pool'][] = $p;
                        break;
                    case 'lolos':
                        $groupedPelamar['lolos'][] = $p;
                        break;
                    case 'tidak_lolos':
                        $groupedPelamar['tidak_lolos'][] = $p;
                        break;
                    default:
                        $groupedPelamar['on_progress'][] = $p;
                        break;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching pelamar: ' . $e->getMessage());
            $groupedPelamar = [
                'on_progress' => [],
                'pending'     => [],
                'talent_pool' => [],
                'lolos'       => [],
                'tidak_lolos' => []
            ];
        }

        return view('admin.list-pelamar', compact('groupedPelamar'));
    }

    public function updateStatusPelamar(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:proses,belum_sesuai,talent_pool,lolos,tidak_lolos'
        ]);

        try {
            Http::withBody(json_encode(['status' => $validated['status']]), 'application/json')
                ->put("{$this->baseUrl}/api/pelamar/{$id}/updateStatus")->throw();

            return redirect()->route('admin.pelamar.list')->with('success', 'Status pelamar berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating status pelamar: ' . $e->getMessage());
            return redirect()->route('admin.pelamar.list')->with('error', 'Gagal memperbarui status pelamar.');
        }
    }

    /** kirim email + update status */
    public function sendEmailPelamar(Request $request, $id)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
            'status'  => 'required|string|in:proses,belum_sesuai,talent_pool,lolos,tidak_lolos'
        ]);

        try {
            // 1) update status
            Http::withBody(json_encode(['status' => $validated['status']]), 'application/json')
                ->put("{$this->baseUrl}/api/pelamar/{$id}/updateStatus")->throw();

            // 2) kirim email
            $payload = [
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'status'  => $validated['status']
            ];
            Http::withBody(json_encode($payload), 'application/json')
                ->post("{$this->baseUrl}/api/pelamar/{$id}/sendEmail")->throw();

            return response()->json(['status' => 'success', 'message' => 'Status diperbarui & email berhasil dikirim.']);
        } catch (\Exception $e) {
            Log::error("Error sendEmailPelamar ID {$id}: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengirim email atau update status.'], 500);
        }
    }

    /** Accept langsung */
    public function acceptPelamar(Request $request, $id)
    {
        return $this->updateStatusPelamar(new Request(['status' => 'lolos']), $id);
    }

    /** Reject langsung */
    public function rejectPelamar(Request $request, $id)
    {
        return $this->updateStatusPelamar(new Request(['status' => 'tidak_lolos']), $id);
    }

    public function pendingPelamar(Request $request, $id)
    {
        return $this->updateStatusPelamar(new Request(['status' => 'belum_sesuai']), $id);
    }

    public function poolPelamar(Request $request, $id)
    {
        return $this->updateStatusPelamar(new Request(['status' => 'talent_pool']), $id);
    }

    public function backToProcess(Request $request, $id)
    {
        return $this->updateStatusPelamar(new Request(['status' => 'proses']), $id);
    }

    /** view detail pelamar */
    public function viewPelamar($id)
    {
        try {
            $pelamar = Http::get("{$this->baseUrl}/api/pelamar/{$id}")->throw()->json();
            $pengalaman = Http::get("{$this->baseUrl}/api/pelamar/{$id}/pengalaman")->json();
        } catch (\Exception $e) {
            Log::error("Error fetching pelamar detail {$id}: " . $e->getMessage());
            $pelamar = [];
            $pengalaman = [];
        }

        return view('admin.view-pelamar', compact('pelamar', 'pengalaman'));
    }

    /* ==========================================================
     * FORM LAMARAN (Public)
     * ========================================================== */
    public function showFormLamaran()
    {
        $jobs = [];
        $formFields = collect();

        try {
            $jobs = Http::get("{$this->baseUrl}/api/jobs")->throw()->json();
            $jobs = is_array($jobs) ? $jobs : [];

            $formFields = FormField::with('job')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching jobs or form fields: ' . $e->getMessage());
        }

        return view('admin.form-lamaran', compact('jobs', 'formFields'));
    }

    /* ==========================================================
     * MANAJEMEN PERTANYAAN TAMBAHAN
     * ========================================================== */
    public function showEditFormLamaran()
    {
        $jobs = [];
        $formFields = collect();

        try {
            $jobs = Http::get("{$this->baseUrl}/api/jobs")->throw()->json();
            $jobs = is_array($jobs) ? $jobs : [];

            $formFields = FormField::with('job')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching jobs or form fields: ' . $e->getMessage());
        }

        return view('admin.edit-form-lamaran', compact('jobs', 'formFields'));
    }

    public function storePertanyaan(Request $request)
    {
        $validated = $request->validate([
            'label'  => 'required|string|max:255',
            'id_job' => 'required|integer|exists:jobs,id',
        ]);

        try {
            FormField::create([
                'id_job'     => $validated['id_job'],
                'label'      => $validated['label'],
                'nama_field' => 'pertanyaan_tambahan_' . Str::slug($validated['label'], '_'),
                'tipe'       => 'textarea',
                'wajib'      => 1,
                'urutan'     => FormField::where('id_job', $validated['id_job'])->max('urutan') + 1,
            ]);

            return back()->with('success', 'Pertanyaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error storing new form field: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pertanyaan.');
        }
    }

    public function updatePertanyaan(Request $request, $id)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
        ]);

        try {
            $formField = FormField::findOrFail($id);
            $formField->label = $validated['label'];
            $formField->save();

            return back()->with('success', 'Pertanyaan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating form field: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui pertanyaan.');
        }
    }

    public function deletePertanyaan($id)
    {
        try {
            FormField::findOrFail($id)->delete();
            return back()->with('success', 'Pertanyaan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting form field: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus pertanyaan.');
        }
    }

    public function viewPerubahan()
    {
        $formData = session('form_lamaran_data', []);
        return view('admin.tampil-new-form-qr', compact('formData'));
    }

    public function getPelamarFile($type, $filename)
    {
        $path = "pelamar/{$type}/{$filename}";

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file(storage_path("app/public/{$path}"));
    }

    public function saveFormBuilder(Request $request)
    {
        $validated = $request->validate([
            'id_job' => 'required',
            'fields'  => 'required',
        ]);
        $payloads = [];
        foreach ($validated['fields'] as $index => $field) {
            $payloads[] = [
                'id_job'     => $validated['id_job'],
                'label'      => $field['label'],
                'nama_field' => 'pertanyaan_tambahan_' . Str::slug($field['label'], '_'),
                'tipe'       => $field['tipe'] ?? 'textarea',
                'wajib'      => $field['wajib'],
                'tampil'     => $field['tampil'],
                'urutan'     => $field['urutan'] ?? $index + 1,
            ];
        }

        try {
            Http::withBody(json_encode($payloads), 'application/json')
                ->post("{$this->baseUrl}/api/field-job")
                ->throw();

            return redirect()->route('admin.jobs.list')->with('success', 'Pertanyaan berhasil disimpan ulang.');
        } catch (\Exception $e) {
            Log::error('Error storing job: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data.');
        }
    }
}
