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
        var completed = true;
        var modal = document.getElementById("myModal");
        function showModal() {
            const showModalEvent = new CustomEvent('myModalShown');
            document.getElementById("myModal").dispatchEvent(showModalEvent);
            modal.style.display = "block";
        }
        $(document).ready(function() {
            $('#import').ajaxForm({
                beforeSend: function() {
                    completed = false
                    //showModal();
                    modal.style.display = "block";
                },
                uploadProgress: function(event, position, total, percentComplete) {

                },
                complete: function(xhr) {
                    completed = true;
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
    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    document.getElementById("myModal").addEventListener('myModalShown', async function() {
        while (!completed) {
            $.ajax({
            url: '/progress', // Replace with your endpoint URL
            type: 'GET',  // Or 'POST' as needed
            success: function(response) {
                console.log('AJAX request success:', response);
//
            },
            error: function(xhr, status, error) {
                console.error('AJAX request error:', error);
                completed = true;
            }
        });
        await delay(1000);
        } 
    });
    
    function updateProgressBar(progress , finishedAt) {
                // $('.progress-bar').html(progress+'%')
                // $('.progress-bar').css('width', progress + '%');

                if (progress === 100) {
                    console.log('finishedAt = ', finishedAt)
                   // $('.finishedAt').html(finishedAt);
                    return;
                }

                // Set up a timeout to periodically fetch progress
                setTimeout(fetchProgress, 1000);
            }
                // AJAX request to fetch progress
                function fetchProgress() {
                let id = $('input').val();
                $.ajax({
                    url: "{{ route('batch', '') }}" + "/"+id,
                    type: 'GET',
                    datatype: "json",
                    success: function (data) {
                        console.log(data)
                        updateProgressBar(data.progress , data.finishedAt);
                        // $('.createdAt').html(data.createdAt);
                    }
                });
            }
                        // Start fetching progress
});

</script>