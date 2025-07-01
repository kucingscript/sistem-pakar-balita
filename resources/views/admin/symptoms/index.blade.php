<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Symptom') }}
        </h2>
    </x-slot>

    <x-slot name="script">
        <script>
            var datatable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{!! url()->current() !!}',
                    data: function(d) {
                        d.disease_id = $('#diseaseFilter').val();
                    }
                },
                order: [
                    [1, 'asc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'code',
                        name: 'symptoms.code',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'description',
                        name: 'description',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'mb',
                        name: 'mb',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'md',
                        name: 'md',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'weight',
                        name: 'weight',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'disease.name',
                        name: 'disease.name',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    },
                ],
            });

            $('#diseaseFilter').on('change', function() {
                datatable.ajax.reload();
            });
        </script>
    </x-slot>

    <div class="py-12 px-4 md:px-8 lg:px-16">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-wrap items-end gap-2 mb-10">
                <a href="{{ route('admin.symptoms.create') }}"
                    class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
                    + Create Symptom
                </a>

                @php
                    $trashed = \App\Models\Symptom::onlyTrashed()->count();
                @endphp

                @if ($trashed > 0)
                    <a href="{{ route('admin.symptoms.trashed') }}"
                        class="px-4 py-2 font-bold text-white bg-red-500 rounded shadow-lg hover:bg-red-700 transition duration-300">
                        View Trashed symptoms ({{ $trashed }})
                    </a>
                @endif

                <div class="ml-auto">
                    <label for="diseaseFilter" class="block text-sm font-medium text-gray-700">Filter by
                        Disease</label>
                    <select id="diseaseFilter" name="disease_id"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="all">All Diseases</option>
                        @foreach ($diseases as $disease)
                            <option value="{{ $disease->id }}">{{ $disease->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="overflow-hidden shadow sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="dataTable">
                        <thead>
                            <tr>
                                <th style="max-width: 1%">No.</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>MB</th>
                                <th>MD</th>
                                <th>Weight</th>
                                <th>Disease</th>
                                <th style="max-width: 1%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
