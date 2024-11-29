<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('phones.phones') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('phones.partials.upload-file-form')
                </div>
            </div>
        </div>
    </div>
    <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
        @foreach ($phones as $phone)
        
            <div class="p-6 flex space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-800">{{ $phone->user->name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{ $phone->created_at->format('j M Y, g:i a') }}</small>
                        </div>
                    </div>
                    @if($phone->status == 'completed')
                        <a href="{{ '/download/'.$phone->file_name }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ __('phones.download_file') }}
                        </a>
                    @else
                        <div class="loading-container relative bg-white bg-opacity-80 flex z-50 opacity-100 transition-opacity duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" class="spinner animate-spin">
                                <circle cx="25" cy="25" r="20" stroke="#3498db" stroke-width="5" fill="none" stroke-linecap="round"/>
                            </svg>
                            <p class="mt-3 text-lg text-blue-500 font-semibold">Procesando...</p>
                        </div>
                    @endif
                    <p class="mt-4 text-lg text-gray-900">{{ $phone->note }}</p>
                    <div class="max-w-xl">
                        @include('phones.partials.delete-file-form')
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                        <!-- Números Procesados -->
                        <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col items-center">
                            <h3 class="text-lg font-medium text-gray-700">Números Procesados</h3>
                            <p class="text-2xl font-semibold text-blue-600">{{$phone->total_phones_proccessed}}</p> <!-- Ejemplo -->
                        </div>
        
                        <!-- Números con Errores -->
                        <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col items-center">
                            <h3 class="text-lg font-medium text-gray-700">Números con Errores</h3>
                            <p class="text-2xl font-semibold text-red-600">{{ $phone->errors }}</p> <!-- Ejemplo -->
                        </div>
        
                        <!-- Duración del Proceso -->
                        <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col items-center">
                            <h3 class="text-lg font-medium text-gray-700">Duración del Proceso</h3>
                            <p class="text-2xl font-semibold text-green-600">35 segundos</p> <!-- Ejemplo -->
                        </div>
        
                        <!-- Link de Descarga (Botón como Link) -->
                        <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col items-center justify-center">
                            <h3 class="text-lg font-medium text-gray-700">Descargar Reporte</h3>
                            <a href="/descargar/reporte.zip" class="mt-4 inline-block px-6 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition duration-300">
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</x-app-layout>
