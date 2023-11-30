<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkRequest extends Model
{
    use HasFactory;

    public function module()
    {
        return $this->belongsTo(Modules::class, 'module_id');
    }
}
