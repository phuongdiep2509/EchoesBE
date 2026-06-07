<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuVucSuKien extends Model
{
    protected $table = 'khu_vuc_su_kien';
    protected $primaryKey = 'MaKhuVuc';
    public $timestamps = false;

    protected $fillable = ['MaSuKien', 'TenKhuVuc', 'SucChua', 'MoTa'];

    public function suKien()
    {
        return $this->belongsTo(SuKien::class, 'MaSuKien', 'MaSuKien');
    }
}
