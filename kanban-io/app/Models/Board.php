<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $guarded = ['id'];

public function project()
{
    return $this->belongsTo(Project::class);
}

public function tasks()
{
    return $this->hasMany(Task::class)->orderBy('position'); // Selalu urutkan kartu dari atas ke bawah
}
}
