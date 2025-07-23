<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use App\Models\Province;

class Regency extends Model {
    protected $table = 'reg_regencies';
    public $timestamps = false;
    public function districts() {
        return $this->hasMany(District::class, 'regency_id');
    }
    public function province() {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
