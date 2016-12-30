<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The transactions that belong to the tag.
     */
    public function transactions()
    {
        return $this->belongsToMany('App\Transaction');
    }
}
