<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // @todo urgh refactor this using collections
    $tagsThisMonth = [];
    $tagsThisWeek = [];

    foreach (App\Transaction::with('tags')->month()->get() as $transaction) {
        foreach ($transaction->tags as $tag) {
            if (! isset($tagsThisMonth[$tag->id])) {
                $tagsThisMonth[$tag->id] = [
                    'name' => $tag->name,
                    'amount' => 0
                ];

                $tagsThisMonth[$tag->id]['amount'] += $transaction->amount;
            }
        }
    }

    foreach (App\Transaction::with('tags')->week()->get() as $transaction) {
        foreach ($transaction->tags as $tag) {
            if (! isset($tagsThisWeek[$tag->id])) {
                $tagsThisWeek[$tag->id] = [
                    'name' => $tag->name,
                    'amount' => 0
                ];

                $tagsThisWeek[$tag->id]['amount'] += $transaction->amount;
            }
        }
    }

    /*dd(App\Transaction::with('tags')->week()->get()->transform(function ($item, $key) {
        return array_combine($item->tags->pluck('name')->toArray(), array_fill(0, $item->tags->count(), $item->amount));
        // return ['amount' => $item->amount, 'tags' => $item->tags->pluck('name')->toArray()];
    }));*/

    return view('dashboard', ['tagsThisMonth' => $tagsThisMonth, 'tagsThisWeek' => $tagsThisWeek]);
});

Route::get('transactions/import', 'TransactionController@import');
Route::resource('transactions', 'TransactionController');
Route::resource('tags', 'TagController');
