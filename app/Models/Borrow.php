<?php
// app/Models/Borrow.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $table = 'borrows';

    protected $fillable = ['book_id', 'user_id', 'borrowed_at', 'returned_at'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isAvailable()
    {
        // Метод проверяет доступность книги.
        // Например, если 'returned_at' равно null, то книга доступна.
        return $this->returned_at === null;
    }
    
}
