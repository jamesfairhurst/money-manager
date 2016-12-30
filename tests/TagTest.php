<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TagTest extends TestCase
{
    use DatabaseMigrations;

    public function testTagsPage()
    {
        $this->visit('/tags')
             ->see('Tags');
    }

    public function testTagsInDb()
    {
        $tags = factory(App\Tag::class, 20)->create()->sortBy('name');

        $this->visit('/tags')
             ->see($tags->first()->name);
    }

    public function testAddTag()
    {
        $this->visit('/tags/create')
             ->type('Food', 'name')
             ->press('Add Tag')
             ->seePageIs('/tags')
             ->see('Tag added!');
    }

    public function testEditTag()
    {
        $tag = factory(App\Tag::class)->create();

        $this->visit('/tags/1/edit')
             ->type('Foods', 'name')
             ->press('Edit Tag')
             ->seePageIs('/tags')
             ->see('Tag updated!');
    }

    public function testDeleteTag()
    {
        $tag = factory(App\Tag::class)->create();

        $this->visit('/tags')
             ->press('Delete')
             ->seePageIs('/tags')
             ->see('Tag deleted!');
    }
}
