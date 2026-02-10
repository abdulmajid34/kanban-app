<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = ['id'];

public function board()
{
    return $this->belongsTo(Board::class);
}

public function creator()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function assignee()
{
    return $this->belongsTo(User::class, 'assigned_to');
}
}
