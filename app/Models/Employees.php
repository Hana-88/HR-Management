<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'age', 'salary', 'gender', 'hired_date', 'job_title', 'manager_id'];

    public static function create(array $array)
    {
    }

    public function manager()
    {
        return $this->belongsTo(Employees::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employees::class, 'manager_id');
    }


}
