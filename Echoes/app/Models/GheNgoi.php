<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GheNgoi extends Model
{
    protected $table = 'ghe_ngoi';
    protected $primaryKey = 'MaGhe';
    public $timestamps = false;

    protected $fillable = ['MaKhuVuc', 'HangGhe', 'SoGhe', 'TrangThai'];
}
