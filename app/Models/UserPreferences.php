<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class UserPreferences extends Model
{
    use Uuids;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
