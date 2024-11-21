    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('phones.phones_database') }}
            </h2>
        </x-slot>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('phones.import_database') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('phones.import_database_explanation') }}
                                </p>
                            </header>
                            @if ($errors->any())
                                <div class="notification is-danger is-light">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif
                            <form id='import' action="{{ url('phones-database/import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" id="file-upload" name="file_upload" style="display: none;">
                                    <x-primary-button type="button"
                                        class="custom-file-upload mt-4 bg-primary">{{ __('phones.chose_file') }}
                                    </x-primary-button>
                                    <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.001-8.584l7.693-7.693A4.5 4.5 0 0016.364 1.035L5.42 11.98" />
                                        </svg>
                                        <span id="selected-file-name"
                                            class="ml-2 text-gray-600">{{ __('phones.no_file_chosen') }}</span>
                                    </div>
                                </div>

                                <x-primary-button id='import' class="mt-4 bg-primary">
                                    {{ __('phones.upload') }}
                                </x-primary-button>
                            </form>
                        </section>

                    </div>
                </div>
            </div>
        </div>
        <div class="relative flex flex-col py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="block bg-transparent w-full p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Id
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    dv
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    AREA
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    RUT
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    PHONE
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($phoneNumbers as $phoneNumber)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $phoneNumber->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $phoneNumber->area }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $phoneNumber->dv }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ $phoneNumber->rut }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ $phoneNumber->number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Confirmed
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="" method="POST">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">show</a>
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">edit</a>
                                            <button class="btn btn-danger">delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td> no hay datos </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            {{ $phoneNumbers->links() }}
        </div>
        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <h2>Importando la Data</h2>
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40" stroke="#4CAF50" stroke-width="4" fill="none">
                        <animate attributeName="stroke-dasharray" from="0 251.33" to="251.33 0" dur="1s"
                            repeatCount="indefinite" />
                    </circle>
                </svg>
                <p> cargando ..</p>
            </div>
        </div>
    </x-app-layout>
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .close-modal {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

    <script>
        document.querySelector('.custom-file-upload').addEventListener('click', function() {
            document.getElementById('file-upload').click();
        });

        document.getElementById('file-upload').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('selected-file-name').textContent = this.files[0].name;
            } else {
                document.getElementById('selected-file-name').textContent = "{{ __('phones.no_file_chosen') }}";
            }
        });

        $(function() {
            $(document).ready(function() {
                $('#import').ajaxForm({
                    beforeSend: function() {
                        modal.style.display = "block";
                        $('#progressBar').val(0)
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        $('#progressBar').val(percentComplete); // Update progress bar value
                    },
                    complete: function(xhr) {
                        modal.style.display = "none";
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: '¡La operación se ha completado con éxito!',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        });
        // Get the modal
        var modal = document.getElementById("myModal");

    </script>
