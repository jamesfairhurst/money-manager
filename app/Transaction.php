<?php

namespace App;

use Carbon\Carbon;
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

    /**
     * Save Transaction Tags
     *
     * @param  string $tags
     * @return boolean
     */
    public function saveTags($tags)
    {
        $explode = explode(',', $tags);

        $tagIds = [];

        foreach ($explode as $tagName) {
            $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
            $tagIds[] = $tag->id;
        }

        return $this->tags()->sync($tagIds);
    }

    /**
     * Get tags for text input
     *
     * @return string
     */
    public function getTagsForInput()
    {
        $tags = $this->tags()->pluck('name');

        if (! $tags->count()) {
            return '';
        }

        return implode(', ', $tags->toArray());
    }

    /**
     * This week's Transactions
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWeek($query)
    {
        return $query->where('date', '>=', Carbon::now()->startOfWeek()->format('Y-m-d'))
            ->where('date', '<=', Carbon::now()->endOfWeek()->format('Y-m-d'));
    }

    /**
     * Last week's Transactions
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastWeek($query)
    {
        return $query->where('date', '>=', Carbon::now()->startOfWeek()->subWeek()->format('Y-m-d'))
            ->where('date', '<=', Carbon::now()->endOfWeek()->subWeek()->format('Y-m-d'));
    }

    /**
     * This month's Transactions
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMonth($query)
    {
        return $query->where('date', '>=', Carbon::now()->startOfMonth()->format('Y-m-d'))
            ->where('date', '<=', Carbon::now()->endOfMonth()->format('Y-m-d'));
    }

    /**
     * This month's Transactions
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastMonth($query)
    {
        return $query->where('date', '>=', Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'))
            ->where('date', '<=', Carbon::now()->endOfMonth()->subMonth()->format('Y-m-d'));
    }
}
