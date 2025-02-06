<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'pakets';

    protected $fillable = [
        'name_paket'
    ];

    public function task()
    {
        return $this->hasMany(Task::class, 'paket_id');
    }
}
