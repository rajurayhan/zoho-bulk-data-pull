<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fields extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'api_name', 'module_id'];

    public function module()
    {
        return $this->belongsTo(Modules::class, 'module_id');
    }
}
