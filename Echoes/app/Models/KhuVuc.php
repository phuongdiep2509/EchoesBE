<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuVuc extends Model
{
    protected $table = 'khu_vuc_su_kien';
    protected $primaryKey = 'MaKhuVuc';
    public $timestamps = false;

    protected $fillable = [
        'MaSuKien',
        'TenKhuVuc',
        'SucChua',
        'MoTa',
    ];

    public function concert()
    {
        return $this->belongsTo(Concert::class, 'MaSuKien', 'MaSuKien');
    }

    public function ticketClasses()
    {
        return $this->hasMany(TicketClass::class, 'MaKhuVuc', 'MaKhuVuc');
    }
}
