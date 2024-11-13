<section class="space-y-6">
    <header>

    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('phones.delete') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('phones.destroy',$phone) }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('phones.Are you sure you want to delete your file?') }}
            </h2>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('phones.cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('phones.delete_file') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
