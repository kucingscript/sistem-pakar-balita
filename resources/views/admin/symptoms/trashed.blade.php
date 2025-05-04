<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            <a href="{{ route('admin.symptoms.index') }}">
                ←
            </a>
            {{ __('Trashed Symptom') }}
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
                        orderable: true
                    },
                    {
                        data: 'description',
                        name: 'description',
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
        </script>
    </x-slot>

    <div class="py-12 px-4 md:px-8 lg:px-16">
        <div class="container mx-auto max-w-7xl">
            <div class="overflow-hidden shadow sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="overflow-x-auto">
                        <table id="dataTable">
                            <thead>
                                <tr>
                                    <th style="max-width: 1%">No.</th>
                                    <th>Code</th>
                                    <th>Description</th>
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
    </div>
</x-app-layout>
