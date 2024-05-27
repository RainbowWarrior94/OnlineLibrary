<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment process') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mx-auto text-center">
                        <form method="POST" action="{{ route('process-payment') }}">
                            @csrf
                            <div class="comment-container" style="margin-bottom: 20px;">
                                <p><strong> {{ $book->title }} </strong> 
                                    (<span style="font-style: italic;">{{ $author->first_name }} {{ $author->last_name }}</span>)</p>
                                <p>Booking Period: {{ $bookingPeriod }}</p>
                            </div>
                            <div class="flex flex-wrap justify-center">
                                <div class="mr-4">
                                    <x-input-label for="firstName">{{ __('First Name:') }}</x-input-label>
                                    <x-text-input label="First Name" name="firstName" type="text" placeholder="First Name" 
                                    style="text-align: center; margin-bottom: 10px; margin-right:10px; width: 200px;" required />
                                </div>
                                <div>
                                    <x-input-label for="lastName">{{ __('Last Name:') }}</x-input-label>
                                    <x-text-input label="Last Name" name="lastName" type="text" placeholder="Last Name" s
                                    tyle="text-align: center; margin-bottom: 10px; width: 200px;" required />
                                </div>
                            </div>
                            <div class="flex flex-wrap justify-center">
                                <div class="mr-4">
                                    <x-input-label for="email">{{ __('Email:') }}</x-input-label>
                                    <x-text-input label="Email" name="email" type="email" placeholder="Email" 
                                    style="text-align: center; margin-bottom: 10px; margin-right:10px; width: 200px;" required value="{{ auth()->user()->email }}" />
                                </div>
                                <div>
                                    <x-input-label for="phone">{{ __('Phone:') }}</x-input-label>
                                    <x-text-input label="Phone" name="phone" type="tel" placeholder="Phone" 
                                    style="text-align: center; margin-bottom: 10px; width: 200px;" required value="{{ auth()->user()->phone_number }}" />
                                </div>
                            </div>
                            <x-input-label for="price">{{ __('Price (zł):') }}</x-input-label>
                            <x-text-input label="Price" name="price" type="text" value="{{ $totalPrice }}" 
                            style="text-align: center; margin-bottom: 10px; width:80px;" readonly />
                            <input type="hidden" name="totalPrice" value="{{ $totalPrice }}">
                            <x-input-label for="cardNumber">{{ __('Card number:') }}</x-input-label>
                            <x-text-input label="Card number" name="cardNumber" type="text" placeholder="Card number" 
                            style="text-align: center; margin-bottom: 10px; width: 200px;" required />
                            <div class="flex flex-wrap justify-center">
                                <div class="mr-4" style="margin-right:10px;">
                                    <x-input-label for="expiryDate">{{ __('Expiration date:') }}</x-input-label>
                                    <x-text-input label="Expiration date" name="expiryDate" type="text" placeholder="MM/YY" 
                                    style="text-align: center; width:100px;" required />
                                </div>
                                <div>
                                    <x-input-label for="cvv">{{ __('CVV:') }}</x-input-label>
                                    <x-text-input label="CVV" name="cvv" type="text" placeholder="CVV" 
                                    style="text-align: center; margin-bottom: 10px; width:60px;" required />
                                </div>
                            </div>
                            <input type="hidden" name="bookingId" value="{{ $borrow->id }}">
                            <x-primary-button>{{ __('Pay') }}</x-primary-button> 
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

