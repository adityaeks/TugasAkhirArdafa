<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;

class Village extends Model {
    protected $table = 'reg_villages';
    public $timestamps = false;
    public function district() {
        return $this->belongsTo(District::class, 'district_id');
    }
}
