<?php

namespace App\Models;

use App\Traits\Uuids;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use Uuids, Filterable;

    protected $guarded = ['id'];
}
