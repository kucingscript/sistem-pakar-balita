<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow flex items-center">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A4 4 0 017 17h10a4 4 0 011.879.804M15 11a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500">Users</p>
                        <p class="text-2xl font-semibold">{{ $userCount }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow flex items-center">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2a4 4 0 018 0v2m-6 4h.01M12 6v2m0 4h.01M4.93 4.93l1.414 1.414M19.07 4.93l-1.414 1.414M4.93 19.07l1.414-1.414M19.07 19.07l-1.414-1.414" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500">Diseases</p>
                        <p class="text-2xl font-semibold">{{ $diseaseCount }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow flex items-center">
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m2 0a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 00-.707.293l-1.414 1.414A1 1 0 0112.586 7H9a2 2 0 00-2 2v3a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500">Symptoms</p>
                        <p class="text-2xl font-semibold">{{ $symptomCount }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow flex items-center">
                    <div class="bg-red-100 text-red-600 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500">Diagnoses</p>
                        <p class="text-2xl font-semibold">{{ $diagnosisCount }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">System Overview</h3>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Sistem ini merupakan <strong>sistem pakar</strong> yang dikembangkan untuk membantu tenaga medis
                        dalam mendiagnosis penyakit pada balita berdasarkan gejala yang teridentifikasi.
                        Metode <strong>Certainty Factor (CF)</strong> digunakan untuk menghitung tingkat kepastian
                        diagnosis berdasarkan kombinasi gejala dan tingkat keyakinan terhadap masing-masing gejala.
                    </p>
                    <p class="text-gray-700 text-sm mt-3 leading-relaxed">
                        Certainty Factor memungkinkan sistem menangani ketidakpastian informasi medis dengan
                        menggabungkan keyakinan pakar serta evaluasi subjektif terhadap gejala yang ditemukan pada
                        pasien.
                        Hasil diagnosis disajikan dalam bentuk daftar kemungkinan penyakit beserta nilai tingkat
                        kepercayaannya (%), sebagai acuan awal dalam proses penegakan diagnosis.
                    </p>
                    <p class="text-gray-700 text-sm mt-3 leading-relaxed">
                        Sistem ini dirancang sebagai <strong>alat bantu klinis</strong> bagi dokter atau tenaga
                        kesehatan untuk meningkatkan efisiensi dan akurasi dalam pengambilan keputusan medis terhadap
                        kasus penyakit balita.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
