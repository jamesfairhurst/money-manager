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
}
