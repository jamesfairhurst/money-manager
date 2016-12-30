<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'date', 'amount'];

    /**
     * The tags that belong to the transaction.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
}
