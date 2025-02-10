<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'task_id',
        'produksi_id',
        'report_name',
        'status',
        'report_date',
        'note',
        'image_report'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function produksi()
    {
        return $this->belongsTo(User::class, 'produksi_id');
    }
}
