<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = factory(App\Tag::class, 10)
            ->create()
            ->each(function ($t) {
                $t->transactions()->attach(App\Transaction::all()->random()->id);
            });
    }
}
