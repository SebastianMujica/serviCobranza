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
    @foreach ($phones as $phone)
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-800">{{ $phone->user->name }}</span>
                        <small class="ml-2 text-sm text-gray-600">{{ $phone->created_at->format('j M Y, g:i a') }}</small>
                    </div>
                </div>
                
                    <div class="grid grid-cols-2 gap-4">

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
                            @php
                                $diferenciaSegundos = $phone->created_at->diffInSeconds($phone->updated_at);
                            @endphp

                            @if ($diferenciaSegundos < 60)
                                <p>  {{ $diferenciaSegundos }} segundos</p>
                            @elseif ($diferenciaSegundos < 3600)
                                <p> {{ floor($diferenciaSegundos / 60) }} minutos</p>
                            @else
                                <p> {{ floor($diferenciaSegundos / 3600) }} horas</p>
                            @endif
                        </div>
                                <!-- Duración del Proceso -->
                        <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col items-center">
                            <h3 class="text-lg font-medium text-gray-700">Nota</h3>
                            <p class="text-2xl font-semibold text-green-600">{{ $phone->note }}</p> <!-- Ejemplo -->
                        </div>
                        <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col items-center">
                            <h3 class="text-lg font-medium text-gray-700">Estatus</h3>
                            <p class="text-2xl font-semibold text-green-600">{{ $phone->status }}</p> <!-- Ejemplo -->
                        </div>
                        <!-- Link de Descarga (Botón como Link) -->
                        <div class="bg-gray-50 rounded-lg shadow p-6 flex flex-col items-center justify-center">
                            <h3 class="text-lg font-medium text-gray-700">Descargar Reporte</h3>
                        @if($phone->status == 'completed')
                            <a href="{{ '/download/'.$phone->file_name }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('phones.download_file') }}
                            </a>
                        @else
                            <p class="mt-3 text-lg text-blue-500 font-semibold">Enlace no disponible.. Procesando...</p>
                        @endif
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
    @endforeach

</x-app-layout>
