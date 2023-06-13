<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyPaper extends Model
{
    use HasFactory;

    public function currency() {
        return $this->belongsTo(Currency::class, 'type', 'id');
    }
}
