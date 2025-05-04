<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            <a href="#!" onclick="window.history.go(-1); return false;">
                ←
            </a>
            {!! __('Diagnosis &raquo; Edit') !!}
        </h2>
    </x-slot>

    <div class="py-12 px-4 md:px-8 lg:px-16">
        <div class="container mx-auto max-w-7xl">
            <div>
                @if ($errors->any())
                    <div class="mb-5" role="alert">
                        <div class="px-4 py-2 font-bold text-white bg-red-500 rounded-t">
                            Something went wrong!
                        </div>
                        <div class="px-4 py-3 text-red-700 bg-red-100 border border-t-0 border-red-400 rounded-b">
                            <p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            </p>
                        </div>
                    </div>
                @endif
                <form class="w-full" action="{{ route('admin.diagnoses.update', $diagnosis->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div id="symptom-inputs">
                        @foreach ($selectedSymptoms as $index => $symptomInput)
                            <div class="symptom-group relative border border-gray-200 rounded p-4">
                                <div class="flex flex-wrap -mx-3">
                                    <div class="w-full mb-6">
                                        <label
                                            class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase">Symptoms*</label>
                                        <select name="symptoms[{{ $index }}][code]"
                                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded focus:outline-none focus:bg-white focus:border-gray-500"
                                            required>
                                            <option value="">Select Symptom Code</option>
                                            @foreach ($symptoms as $symptom)
                                                <option value="{{ $symptom->code }}"
                                                    {{ $symptom->code == $symptomInput['code'] ? 'selected' : '' }}>
                                                    {{ $symptom->code }} - {{ $symptom->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-full">
                                        <label
                                            class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase">Confidence
                                            Level*</label>
                                        <select name="symptoms[{{ $index }}][confidence]"
                                            class="block w-full px-4 py-3 bg-gray-200 border border-gray-200 text-gray-700 rounded focus:outline-none focus:bg-white focus:border-gray-500"
                                            required>
                                            <option value="">Select Confidence</option>
                                            @foreach ([0.0, 0.2, 0.4, 0.6, 0.8, 1.0] as $conf)
                                                <option value="{{ $conf }}"
                                                    {{ $symptomInput['confidence'] == $conf ? 'selected' : '' }}>
                                                    {{ $conf * 100 }}% -
                                                    {{ [
                                                        'Tidak Pernah Terjadi',
                                                        'Hampir Tidak Pernah',
                                                        'Kadang-kadang',
                                                        'Sering Terjadi',
                                                        'Hampir Selalu',
                                                        'Selalu Terjadi',
                                                    ][(int) ($conf * 5)] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if ($index > 0)
                                    <button type="button" onclick="removeSymptom(this)"
                                        class="absolute top-0 right-0 mt-2 mr-2 px-2 py-1 text-sm font-bold text-white bg-red-500 rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-start mb-6">
                        <button type="button" onclick="addSymptomInput()"
                            class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
                            + Add Symptom
                        </button>
                    </div>

                    <div class="flex flex-wrap mb-6 -mx-3">
                        <div class="w-full px-3 text-right">
                            <button type="submit"
                                class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
                                Save Diagnosis
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let index = {{ count($selectedSymptoms) }};

        function addSymptomInput() {
            const container = document.getElementById('symptom-inputs');
            const group = document.createElement('div');
            group.className = 'symptom-group relative border border-gray-200 rounded p-4';

            const symptomOptions = `@foreach ($symptoms as $symptom)
                <option value="{{ $symptom->code }}">{{ $symptom->code }} - {{ $symptom->description }}</option>
            @endforeach`;


            const confidenceOptions = `
                <option value="">Select Confidence</option>
                <option value="0.0">0% - Tidak Pernah Terjadi</option>
                <option value="0.2">20% - Hampir Tidak Pernah</option>
                <option value="0.4">40% - Kadang-kadang</option>
                <option value="0.6">60% - Sering Terjadi</option>
                <option value="0.8">80% - Hampir Selalu</option>
                <option value="1.0">100% - Selalu Terjadi</option>
            `;

            group.innerHTML = `
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full mb-6">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase">
                            Symptom Code*
                        </label>
                        <select name="symptoms[${index}][code]"
                            class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded focus:outline-none focus:bg-white focus:border-gray-500"
                            required>
                            <option value="">Select Symptom Code</option>
                            ${symptomOptions}
                        </select>
                    </div>

                    <div class="w-full">
                        <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase">
                            Confidence Level*
                        </label>
                        <select name="symptoms[${index}][confidence]"
                            class="block w-full px-4 py-3 bg-gray-200 border border-gray-200 text-gray-700 rounded focus:outline-none focus:bg-white focus:border-gray-500"
                            required>
                            ${confidenceOptions}
                        </select>
                    </div>
                </div>
                <button type="button" onclick="removeSymptom(this)"
                    class="absolute top-0 right-0 mt-2 mr-2 px-2 py-1 text-sm font-bold text-white bg-red-500 rounded hover:bg-red-700">
                    Delete
                </button>
            `;

            container.appendChild(group);
            index++;
        }

        function removeSymptom(button) {
            const group = button.closest('.symptom-group');
            if (group) {
                group.remove();
            }
        }
    </script>
</x-app-layout>
