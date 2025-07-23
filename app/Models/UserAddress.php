<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';

    public function province() {
        return $this->belongsTo(Province::class, 'province_id');
    }
    public function regency() {
        return $this->belongsTo(Regency::class, 'regency_id');
    }
    public function district() {
        return $this->belongsTo(District::class, 'district_id');
    }
    public function village() {
        return $this->belongsTo(Village::class, 'village_id');
    }
}
