<x-guest-layout>
    <div class="py-12 flex justify-center">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="text-align: center;">
                    <h3 style="margin-bottom: 20px;">The book was successfully returned </h3>
                    <div style="display: flex; justify-content: center;">
                        <x-primary-button style="margin-right: 10px;">
                            <a href="/">{{ __('return to catalog') }}</a>
                        </x-primary-button>
                        <x-primary-button>
                            <a href="{{ $file_url }}" target="_blank">{{ __('view your EDI File') }}</a>
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
