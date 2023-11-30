<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkRequest extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'module_id', 'status', 'response', 'status_response'];

    public function module()
    {
        return $this->belongsTo(Modules::class, 'module_id');
    }
}
