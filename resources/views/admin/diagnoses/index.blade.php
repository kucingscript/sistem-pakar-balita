<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Diagnosis') }}
        </h2>
    </x-slot>

    <x-slot name="script">
        <script>
            var datatable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                order: [
                    [1, 'desc']
                ],
                ajax: {
                    url: '{!! url()->current() !!}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'symptoms',
                        name: 'symptoms',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'result_disease',
                        name: 'result_disease',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'result_percentage',
                        name: 'result_percentage',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, row) {
                            return data + '%';
                        }
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
        </script>
    </x-slot>

    <div class="py-12 px-4 md:px-8 lg:px-16">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-wrap gap-2 mb-10">
                <a href="{{ route('admin.diagnoses.create') }}"
                    class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
                    + Create Diagnosis
                </a>

                @php
                    $trashed = \App\Models\Diagnosis::onlyTrashed()->count();
                @endphp

                @if ($trashed > 0)
                    <a href="{{ route('admin.diagnoses.trashed') }}"
                        class="px-4 py-2 font-bold text-white bg-red-500 rounded shadow-lg hover:bg-red-700 transition duration-300">
                        View Trashed Diagnosis ({{ $trashed }})
                    </a>
                @endif
            </div>
            <div class="overflow-hidden shadow sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="dataTable">
                        <thead>
                            <tr>
                                <th style="max-width: 1%">No.</th>
                                <th>Symptoms</th>
                                <th>Disease</th>
                                <th>Percentage</th>
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
