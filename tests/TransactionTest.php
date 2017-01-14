<?php

use App\Transaction;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    public function testTransactionsPage()
    {
        $this->visit('/transactions')
             ->see('Transactions');
    }

    public function testTransactionsInDb()
    {
        $transactions = factory(Transaction::class, 20)->create()->sortByDesc('date');

        $this->visit('/transactions')
             ->see($transactions->first()->name);
    }

    public function testAddTransaction()
    {
        $this->visit('/transactions/create')
             ->type('Food shop', 'name')
             ->type('Weekly big food shop at Morrisons', 'description')
             ->type(Carbon::now()->format('Y-m-d'), 'date')
             ->type(50, 'amount')
             ->press('Add Transaction')
             ->seePageIs('/transactions')
             ->see('Transaction added!');
    }

    public function testEditTransaction()
    {
        $transaction = factory(Transaction::class)->create();

        $this->visit('/transactions/1/edit')
             ->type(50, 'amount')
             ->press('Edit Transaction')
             ->seePageIs('/transactions')
             ->see('Transaction updated!');
    }

    public function testDeleteTransaction()
    {
        $transaction = factory(Transaction::class)->create();

        $this->visit('/transactions')
             ->press('Delete')
             ->seePageIs('/transactions')
             ->see('Transaction deleted!');
    }

    public function testAddTransactionWithTags()
    {
        $this->visit('/transactions/create')
             ->type('Food shop', 'name')
             ->type('Weekly big food shop at Morrisons', 'description')
             ->type(Carbon::now()->format('Y-m-d'), 'date')
             ->type(50, 'amount')
             ->type('tag1, tag2', 'tags')
             ->press('Add Transaction')
             ->seePageIs('/transactions')
             ->see('Transaction added!');

        $this->assertEquals(2, Transaction::find(1)->tags()->count());
    }

    public function testTransactionsImportPage()
    {
        $this->visit('/transactions/import')
             ->see('Import Transactions');
    }

    public function testTransactionsPageWithTags()
    {
        $transactions = factory(Transaction::class, 5)
                            ->create()
                            ->each(function ($t) {
                                $t->tags()->save(factory(App\Tag::class)->make());
                            });

        $this->visit('/transactions')
             ->see($transactions->first()->tags->first()->name);
    }

    public function testTransactionsPageWithTagFilter()
    {
        $transactions = factory(Transaction::class, 5)
                            ->create()
                            ->each(function ($t) {
                                $t->tags()->save(factory(App\Tag::class)->make());
                            });

        $numberOfTransactionsRows = $this->visit('/transactions?tag=' . Tag::all()->first()->id)->crawler()->filter('tbody > tr')->count();
        $this->assertEquals(count(Tag::all()->first()->transactions), $numberOfTransactionsRows);
    }
}
