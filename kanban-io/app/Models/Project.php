<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id'];

public function workspace()
{
    return $this->belongsTo(Workspace::class);
}

public function boards()
{
    return $this->hasMany(Board::class)->orderBy('position'); // Selalu urutkan berdasarkan posisi kolom
}
}
