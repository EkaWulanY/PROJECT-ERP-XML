<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelamar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #e5e7eb;
        }

        .hover-effect-btn:hover {
            background-color: #4E71FF;
            transition: background-color 0.3s ease;
        }

        /* Scroll area gabungan */
        .scroll-container {
            max-height: 85vh; /* tinggi maksimal 85% layar */
            overflow-y: auto;
            padding: 1rem;
        }

        .scroll-container::-webkit-scrollbar {
            width: 8px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background-color: #9ca3af;
            border-radius: 4px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f3f4f6;
        }
    </style>
</head>

<body class="bg-gray-200">
    <!-- Navbar -->
    <div class="bg-[#072A75] text-white p-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2 rounded-full">
            <span class="text-xl font-bold">XMLTRONIK-KARIR</span>
        </div>
        <div class="flex items-center">
            <span class="mr-2">Pelamar</span>
            <svg class="h-8 w-8 rounded-full border-2 border-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>

    <main class="container mx-auto px-4 my-6">
        <!-- Container utama dengan scroll -->
        <div class="bg-white rounded-xl shadow-md scroll-container">
            <!-- Filter Section -->
            <section class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="relative w-full">
                        <input type="text" id="keywords" placeholder="Keywords"
                            class="w-full p-2 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </div>
                    <button onclick="searchJobs()"
                        class="bg-indigo-600 text-white p-2 rounded-lg btn-hover flex-shrink-0 flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        <span>Search</span>
                    </button>
                </div>
                <div class="flex flex-wrap items-center mt-4 space-x-4">
                    <select id="education-filter" class="p-2 border rounded-lg w-full md:w-auto mt-2 md:mt-0">
                        <option value="">Pendidikan</option>
                        <option value="SMK">SMA/SMK</option>
                        <option value="D3">D3</option>
                        <option value="D4">D4</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                    </select>
                    <select id="location-filter" class="p-2 border rounded-lg w-full md:w-auto mt-2 md:mt-0">
                        <option value="">Lokasi</option>
                        <option value="Cilacap">Cilacap</option>
                        <option value="Banyumas">Banyumas</option>
                        <option value="Tegal">Tegal</option>
                        <option value="Purbalingga">Purbalingga</option>
                    </select>
                </div>
            </section>

            <!-- Job Listings -->
            <section id="job-listings" class="p-6 space-y-6">
            </section>
        </div>
    </main>

    <!-- Modal Detail Job -->
    <div id="job-detail-modal" class="modal">
        <div class="modal-content card-shadow">
            <div class="flex justify-between items-start">
                <h2 class="text-2xl font-bold text-gray-800" id="modal-job-title"></h2>
            </div>
            <div id="modal-job-content" class="mt-4 text-gray-700">
            </div>
        </div>
    </div>

    <!-- Script (tidak diubah) -->
    <script>
        const API_URL = 'http://localhost:8080/api';
        let allJobs = [];

        async function fetchActiveJobs() {
            try {
                const response = await fetch('/proxy/jobs');
                allJobs = await response.json();
                renderJobs(allJobs);
            } catch (error) {
                console.error("Error fetching jobs:", error);
                document.getElementById("job-listings").innerHTML =
                    "<p class='text-red-500'>Gagal memuat lowongan kerja. Silakan coba lagi nanti.</p>";
            }
        }

        function renderJobs(jobs) {
            const container = document.getElementById('job-listings');
            container.innerHTML = '';

            if (jobs.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500">Belum ada lowongan kerja yang tersedia saat ini.</p>';
                return;
            }

            jobs.forEach(job => {
                const jobCard = `
                    <div class="bg-white rounded-xl shadow p-6 flex flex-col space-y-4 border border-gray-200">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">${job.posisi}</h3>
                            <p class="text-gray-600 font-medium">${job.perusahaan || 'PT XMLTronik'}</p>
                        </div>
                        
                        <div class="flex items-center text-gray-500 text-sm space-x-6">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.75s-7-5.5-7-10.5a7 7 0 0 1 14 0c0 5-7 10.5-7 10.5z" />
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <span>${job.lokasi || 'Cilacap'}</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                                <span>${timeAgo(job.tanggal_post)}</span>
                            </div>
                        </div>

                        <p class="text-gray-700">${job.deskripsi}</p>

                        <div class="flex justify-between items-center">
                            <button onclick="window.location.href='/lowongan-kerja/${job.id_job}'" 
                                class="text-blue-600 font-semibold hover:underline">
                                View More
                            </button>                     
                            <button onclick="applyJob(${job.id_job})" class="bg-orange-500 text-white px-4 py-2 rounded-lg font-semibold">Lamar Sekarang</button>
                        </div>
                    </div>
                `;
                container.innerHTML += jobCard;
            });
        }

        async function showJobDetails(jobId) {
            try {
                const jobResponse = await fetch(`${API_URL}/jobs/${jobId}`);
                const jobData = await jobResponse.json();
                if (!jobResponse.ok) throw new Error('Failed to load job details.');

                const fieldResponse = await fetch(`${API_URL}/field-job/byJob/${jobId}`);
                const fieldData = await fieldResponse.json();
                let fieldHtml = '';
                if (fieldData.data && fieldData.data.length > 0) {
                    fieldData.data.forEach(field => {
                        fieldHtml += `<h4 class="font-semibold mt-4">${field.judul}</h4>`;
                        fieldHtml += `<p>${field.deskripsi}</p>`;
                    });
                } else {
                    fieldHtml = '<p>Detail kualifikasi dan job desk tidak tersedia.</p>';
                }

                document.getElementById('modal-job-title').innerText = jobData.posisi;
                document.getElementById('modal-job-content').innerHTML = `
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-100 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Deskripsi Pekerjaan</h4>
                            <p>${jobData.deskripsi || 'Tidak ada deskripsi pekerjaan.'}</p>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Tanggal Posting</h4>
                            <p>${jobData.tanggal_post}</p>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Batas Lamaran</h4>
                            <p>${jobData.batas_lamaran}</p>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Informasi Tambahan</h4>
                            ${fieldHtml}
                        </div>
                    </div>
                `;
                document.getElementById('job-detail-modal').style.display = 'flex';
            } catch (error) {
                console.error('Error fetching job details:', error);
                alert('Gagal memuat detail lowongan. Silakan coba lagi.');
            }
        }

        function closeModal() {
            document.getElementById('job-detail-modal').style.display = 'none';
        }

        function applyJob(jobId) {
            window.location.href = `/pelamar/form?id_job=${jobId}`;
        }

        function timeAgo(dateString) {
            const now = new Date();
            const past = new Date(dateString);
            const diffInSeconds = Math.floor((now - past) / 1000);
            const intervals = [
                { label: 'tahun', seconds: 31536000 },
                { label: 'bulan', seconds: 2592000 },
                { label: 'hari', seconds: 86400 },
                { label: 'jam', seconds: 3600 },
                { label: 'menit', seconds: 60 },
                { label: 'detik', seconds: 1 }
            ];
            for (let i = 0; i < intervals.length; i++) {
                const interval = intervals[i];
                const count = Math.floor(diffInSeconds / interval.seconds);
                if (count >= 1) {
                    return `${count} ${interval.label} yang lalu`;
                }
            }
            return 'Baru saja diposting';
        }

        function searchJobs() {
            const keywords = document.getElementById('keywords').value.toLowerCase();
            const education = document.getElementById('education-filter').value.toLowerCase();
            const location = document.getElementById('location-filter').value.toLowerCase();
            const filteredJobs = allJobs.filter(job => {
                const title = job.posisi?.toLowerCase() || '';
                const desc = job.deskripsi?.toLowerCase() || '';
                const jobEducation = job.pendidikan_min?.toLowerCase() || '';
                const jobLocation = job.lokasi?.toLowerCase() || '';
                const matchKeyword = (title.includes(keywords) || desc.includes(keywords));
                const matchEdu = (education === '' || jobEducation.includes(education));
                const matchLoc = (location === '' || jobLocation.includes(location));
                return matchKeyword && matchEdu && matchLoc;
            });
            renderJobs(filteredJobs);
        }

        document.addEventListener("DOMContentLoaded", () => {
            fetchActiveJobs();
            document.getElementById("education-filter").addEventListener("change", searchJobs);
            document.getElementById("location-filter").addEventListener("change", searchJobs);
        });
    </script>
</body>
</html>
