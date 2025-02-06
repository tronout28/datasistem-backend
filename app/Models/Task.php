<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    
    protected $fillable = [
        'marketing_id', 
        'produksi_id', 
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

    /**
     * Relasi ke User sebagai Produksi.
     */
    public function produksi()
    {
        return $this->belongsTo(User::class, 'produksi_id');
    }

    /**
     * Relasi ke Paket.
     */
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }
}
