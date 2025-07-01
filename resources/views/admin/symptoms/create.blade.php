<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            <a href="#!" onclick="window.history.go(-1); return false;">
                ←
            </a>
            {!! __('Symptom &raquo; Create') !!}
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
                <form class="w-full" action="{{ route('admin.symptoms.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="grid-last-name">
                                Code*
                            </label>
                            <input value="{{ old('code') }}" name="code"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name" type="text" placeholder="Code" required>
                            <div class="mt-2 text-sm text-gray-500">
                                Symptom code. ex: G01, G10. Required. Max 255 characters.
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="grid-last-name">
                                Description*
                            </label>
                            <input value="{{ old('description') }}" name="description"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name" type="text" placeholder="Description" required>
                            <div class="mt-2 text-sm text-gray-500">
                                Description. ex: Dehidrase, kulit kering. Required. Max 255 characters.
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="grid-last-name">
                                Measure Belief (MB)*
                            </label>
                            <input value="{{ old('mb') }}" name="mb"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name" type="number" placeholder="MB" required step="0.01"
                                min="0" max="1">
                            <div class="mt-2 text-sm text-gray-500">
                                MB. ex: 0.5,0.7 Required. Min 0 Max 1.
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="grid-last-name">
                                Measure Disbelief (MD)*
                            </label>
                            <input value="{{ old('md') }}" name="md"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name" type="number" placeholder="MD" required step="0.01"
                                min="0" max="1">
                            <div class="mt-2 text-sm text-gray-500">
                                MD. ex: 0.5,0.7 Required. Min 0 Max 1.
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="grid-last-name">
                                Disease*
                            </label>
                            <select name="disease_id" id="" required
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500">
                                <option value="">Select Disease</option>
                                @foreach ($diseases as $ds)
                                    <option value="{{ $ds->id }}"
                                        {{ old('disease_id') == $ds->id ? 'selected' : '' }}>{{ $ds->code }} -
                                        {{ $ds->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-2 text-sm text-gray-500">
                                Disease. Required.
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap mb-6 -mx-3">
                        <div class="w-full px-3 text-right">
                            <button type="submit"
                                class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
                                Save Symptom
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
