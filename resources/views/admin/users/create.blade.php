<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            <a href="#!" onclick="window.history.go(-1); return false;">
                ←
            </a>
            {!! __('User &raquo; Create') !!}
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
                <form class="w-full" action="{{ route('admin.users.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="grid-last-name">
                                Name*
                            </label>
                            <input value="{{ old('name') }}" name="name"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name" type="text" placeholder="Name" required>
                            <div class="mt-2 text-sm text-gray-500">
                                Name. ex: John Doe, Jane Doe. Required. Max 255 characters.
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="grid-last-name">
                                Email*
                            </label>
                            <input value="{{ old('email') }}" name="email"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name" type="email" placeholder="Email" required>
                            <div class="mt-2 text-sm text-gray-500">
                                Email. ex: johndoe@gmail.com, janedoe@email.com. Required. Valid email.
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase"
                                for="grid-last-name">
                                Password*
                            </label>
                            <input value="{{ old('password') }}" name="password"
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name" type="password" placeholder="********" required>
                            <div class="mt-2 text-sm text-gray-500">
                                Password.. Required. Min 8 characters.
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap px-3 mt-4 mb-6 -mx-3">
                        <div class="w-full">
                            <label class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase">
                                Role*
                            </label>
                            <select name="role" required
                                class="block w-full px-4 py-3 leading-tight text-gray-700 bg-gray-200 border border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-gray-500">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-2 text-sm text-gray-500">
                                Assign user role. Required.
                            </div>
                        </div>
                    </div>


                    <div class="flex flex-wrap mb-6 -mx-3">
                        <div class="w-full px-3 text-right">
                            <button type="submit"
                                class="px-4 py-2 font-bold text-white bg-green-500 rounded shadow-lg hover:bg-green-700">
                                Save User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
