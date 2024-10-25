<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Please upload the file') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Proccess a .xls or .txt file checks for news phones and generate a blacklist new list") }}
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
    <form action="{{ route('phones.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file_upload">
        
        <button type="submit">Upload</button>
    </form>
</section>
