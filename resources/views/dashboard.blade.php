<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Choose a book to suit your taste') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="container">
            <div class="flex items-center justify-center h-screen">
                <form action="{{ route('search') }}" method="GET" class="form-container">
                    @csrf
                    <div class="form-input">
                        <x-text-input type="text" name="title" id="title" class="form-input mt-1 block w-full" placeholder="Title"/>
                    </div>
                    
                    <div class="form-input">
                        <x-text-input type="text" name="author" id="author" class="form-input mt-1 block w-full" placeholder="Author"/>
                    </div>
                    <div class="form-input">
                        <x-text-input type="text" name="category" id="category" class="form-input mt-1 block w-full" placeholder="Category"/>
                    </div>
            
                    <button type="submit" class="btn-primary" style="margin-bottom:20px;">
                        Search
                    </button>
                </form>
            </div>
            <div class="row">
                @foreach($books as $book)
                    <div class="py-2">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <h3 class="text-lg font-semibold" style="font-size: 1.5rem; font-weight: 600;">{{ $book->title }}</h3>
                                    <div class="star-rating" data-rating="{{ $book->reviews->avg('rating') }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                          @if ($i <= round($book->reviews->avg('rating')))
                                            <span class="star">&#9733;</span>
                                          @else
                                            <span class="star">&#9734;</span>
                                          @endif
                                        @endfor
                                      </div>
                                    <p><strong>Author: </strong>{{ $book->author->first_name }} {{ $book->author->last_name }}</p>
                                    <p><strong>Category: </strong>{{ $book->category->category_name }}</p>
                                    <p><strong>ISBN: </strong>{{ $book->isbn }}</p>
                                    <p><strong>Publication Year: </strong>{{ $book->publication_year }}</p>
                                    <p><strong>Description: </strong>{{ $book->description }}</p>

                                    @if ($book->isAvailable())
                                    <div class="d-flex align-items-center">
                                        <p class="availability-yes">Available</p>
                                        <form action="{{ route('borrow_book', ['id' => $book->id]) }}" method="POST" class="inline-form">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                            <button type="submit" class="borrow-button">Borrow</button>
                                        </form>
                                    </div>
                                @else
                                    <p class="availability-no">Not Available</p>
                                @endif
                                    @if ($book->reviews->count() > 0)
                                        <button class="custom-button" data-bs-toggle="collapse" data-bs-target="#reviewCollapse{{ $book->id }}">
                                            Reviews
                                        </button>
                                        <div id="reviewCollapse{{ $book->id }}" class="collapse mt-3">
                                            @foreach ($book->reviews as $review)
                                            <div class="review-container">
                                                <p>{{ $review->user->name }}</p>
                                                <div class="rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <span class="star">&#9733;</span> 
                                                        @else
                                                            <span class="star">&#9734;</span>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <div class="comment-container">
                                                    <p class="comment">{{ $review->comment }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                    @else
                                        <p>No reviews available</p>
                                    @endif
                                    <form action="{{ route('add_comment', ['id' => $book->id]) }}" method="POST" class="mt-3">
                                        @csrf
                                        <div class="mb-2">                             
                                            <div class="input-group">
                                                <button type="button" class="custom-button" data-bs-toggle="collapse" data-bs-target="#commentCollapse{{ $book->id }}">
                                                    Add your opinion
                                                </button>
                                            </div>
                                            <div class="mb-2">
                                                <div class="collapse" id="commentCollapse{{ $book->id }}">
                                                    <textarea name="comment" id="comment" class="form-control" required></textarea>
                                                    <div class="form-group row">
                                                        <label for="rating" class="col-sm-1 col-form-label" style="margin-left: 10px; padding: 7px; width: auto;">Rating:</label>
                                                        <div class="col-sm-1" style="padding: 0;">
                                                            <select name="rating" id="rating" class="form-control" style="margin: 3px 3px 10px 3px;" required>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="custom-button">Submit Comment</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>











{{-- <x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{ __('All Books') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="container">
          <div class="row">
              @foreach($books as $book)
                  <div class="col-sm-4 col-lg-4 mb-4">
                      <div class="mb-4 mx-4 p-4 border border-gray-300 rounded">
                          <h3 class="text-lg font-semibold">{{ $book->title }}</h3>
                          <p>Author: {{ $book->first_name }} {{ $book->last_name }}</p>
                          <p>Category: {{ $book->category_name }}</p>
                          <p>ISBN: {{ $book->isbn }}</p>
                          <p>Publication Year: {{ $book->publication_year }}</p>
                          <p>Description: {{ $book->description }}</p>
                      </div>
                  </div>
              @endforeach
          </div>
      </div>
  </div>
</x-app-layout> --}}





