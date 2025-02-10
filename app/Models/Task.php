<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'marketing_id',
        'produksi_id_1',
        'produksi_id_2',
        'produksi_id_3',
        'paket_id',
        'task_name',
        'bisnis_name',
        'bisnis_domain',
        'queue',
        'status',
        'deadline',
        'join_date',
        'note'
    ];

    public function marketing()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }

    public function produksi1()
    {
        return $this->belongsTo(User::class, 'produksi_id_1');
    }

    public function produksi2()
    {
        return $this->belongsTo(User::class, 'produksi_id_2');
    }

    public function produksi3()
    {
        return $this->belongsTo(User::class, 'produksi_id_3');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }
}
