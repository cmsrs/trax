<?php
namespace App;

use App\Trip;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'make',
        'model',
        'year'
    ];    

    public function trips()
    {
        return $this->hasMany('App\Trip');
    }    

}
