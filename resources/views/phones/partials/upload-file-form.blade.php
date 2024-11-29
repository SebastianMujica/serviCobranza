<section>
    <header>

        <h2 class="text-lg font-medium text-gray-900">

            {{ __('phones.please') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('phones.process') }}
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
    <form id='import' action="{{ route('phones.index') }}" method="POST" enctype="multipart/form-data">
        @csrf

          <input type="file" id="file-upload" name="file_upload" style="display: none;">
          <x-primary-button   type="button" class="custom-file-upload mt-4 bg-primary">{{ __('phones.chose_file') }}</x-primary-button>
          
                  <div class="mt-2 flex items-center"> 
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.001-8.584l7.693-7.693A4.5 4.5 0 0016.364 1.035L5.42 11.98" />
            </svg>
            <span id="selected-file-name" class="ml-2 text-gray-600">{{ __('phones.no_file_chosen') }}</span>
        </div>
        <textarea
        name="note"
        placeholder="{{ __('phones.observations') }}"
        class="mt-6 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
    >{{ old('note') }}</textarea>
        <x-primary-button class="mt-4 bg-primary">{{ __('phones.upload') }}</x-primary-button>
    </form>
    @if (session('id'))
        <input type="hidden" value="{{ session('id') }}">
     @endif
    <div id="progress-container">
        <div id="progress-bar"></div>
    </div>
</section>

<script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>


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
    const formulario = document.getElementById('import');
    const mensaje = document.getElementById('mensajeProcesando');

    formulario.addEventListener('submit', function () {
        Swal.fire({  
            title: "Procesando",
            text: "Por favor, espere un momento",
            icon: "info",
            showConfirmButton: false, // Oculta el botón
            closeOnEsc: false,
            closeOnClickOutside: false,
            onOpen: () => {
                Swal.showLoading();
                 }
            });    
    });
    
</script>