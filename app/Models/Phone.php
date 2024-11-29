<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Phone extends Model
{
    use HasFactory;
    protected $fillable = [
        'note',
        'file_url',
        'file_name',
        'new_black_list',
        'status',
        'errors',
        'total_phones_proccessed',
        'total',
        'user_id',
    ];
    protected $table = 'phone';
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
