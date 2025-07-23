<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Village;
use App\Models\Regency;

class District extends Model {
    protected $table = 'reg_districts';
    public $timestamps = false;
    public function villages() {
        return $this->hasMany(Village::class, 'district_id');
    }
    public function regency() {
        return $this->belongsTo(Regency::class, 'regency_id');
    }
}
