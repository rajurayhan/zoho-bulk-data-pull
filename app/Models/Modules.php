<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    use HasFactory;

    public function fields()
    {
        return $this->hasMany(Fields::class, 'module_id');
    }

    public function bulk_requests()
    {
        return $this->hasMany(BulkRequest::class, 'module_id');
    }
}
