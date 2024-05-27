<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrow;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function showAllBooks(Request $request)
    {
        $title = $request->input('title');
        $author = $request->input('author');
        $category = $request->input('category');

        $query = Book::with(['reviews', 'author', 'category'])
            ->when($title, function ($q) use ($title) {
                $q->where('title', 'like', "%{$title}%");
            })
            ->when($author, function ($q) use ($author) {
                $q->whereHas('author', function ($q) use ($author) {
                    $q->whereRaw('LOWER(CONCAT(first_name, " ", last_name)) LIKE ?', ["%{$author}%"]);
                });
            })
            ->when($category, function ($q) use ($category) {
                $q->whereHas('category', function ($q) use ($category) {
                    $q->where('category_name', 'like', "%{$category}%");
                });
            })
            ->get();
        
        return view('dashboard', ['books' => $query]);
    }
    public function addComment(Request $request, $bookId)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $book = Book::with('reviews')->find($bookId);

        if (!$book) {
            abort(404, 'book not found');
        }
        
        $userId = Auth::id(); 

        Review::create([
            'book_id' => $bookId,
            'comment' => $request->input('comment'),
            'user_id' => $userId,
            'rating' => $request->input('rating')
        ]);
        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function borrowBook(Request $request, $bookId)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);
    $book = Book::findOrFail($bookId);

    if ($book->isAvailable()) {
        $book->borrows()->create([
            'user_id' => $request->input('user_id'),
        ]);

        return redirect()->back()->with('success', 'Book borrowed successfully');
    } else {
        return redirect()->back()->with('error', 'Book not available for borrowing');
    }
}
public function showAvailability($bookId)
{
    $book = Book::findOrFail($bookId);
    $isAvailable = !$book->borrows()->whereNull('returned_at')->exists();
    return view('availability', ['isAvailable' => $isAvailable]);
}

public function showBorrowedBooks()
{
    if (Auth::check()) {
        $user = Auth::user();
        $activeBorrowedBooks = $user->borrowedBooks()
        ->withPivot('id', 'book_id', 'user_id', 'borrowed_at', 'returned_at') 
        ->whereNull('borrows.returned_at')
        ->get();
        return view('borrowed-books', compact('activeBorrowedBooks'));
    } else {
        return redirect()->route('login')->with('error', 'Please log in to view your borrowed books.');
    }
}
    public function showBookingHistory()
    {
        $user = Auth::user();
        $bookingHistory = $user->borrowedBooks()
            ->whereNotNull('borrows.borrowed_at')
            ->whereNotNull('borrows.returned_at')
            ->get();
        return view('history', ['bookingHistory' => $bookingHistory]);
    }
}






    // public function returnBook(Request $request, $bookId)
    // {
    //     // Валидация данных
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //     ]);

    //     // Найдите книгу по $bookId
    //     $book = Book::findOrFail($bookId);

    //     // Проверьте, была ли книга забронирована пользователем
    //     $borrow = $book->borrows()
    //         ->where('user_id', $request->input('user_id'))
    //         ->whereNull('returned_at')
    //         ->first();

    //     if ($borrow) {
    //         // Установите дату возврата в текущую дату и время
    //         $borrow->update([
    //             'returned_at' => now(),
    //         ]);

    //         return redirect()->back()->with('success', 'Book returned successfully');
    //     } else {
    //         return redirect()->back()->with('error', 'Book not borrowed by the user');
    //     }
    // }



// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

// class bookController extends Controller
// {
//     public function showAllbooks()
//     {
//         $books = DB::table('books')
//             ->join('authors', 'books.author_id', '=', 'authors.id')
//             ->join('categories', 'books.category_id', '=', 'categories.id')
//             ->select('books.*', 'authors.first_name', 'authors.last_name', 'categories.category_name')
//             ->get();

//         // dd($books);

//         return view('dashboard', ['books' => $books]);
//     }
// }
