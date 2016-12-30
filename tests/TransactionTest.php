<?php

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
        $transactions = factory(App\Transaction::class, 20)->create()->sortByDesc('date');

        $this->visit('/transactions')
             ->see($transactions->first()->name);
    }

    public function testNewTransaction()
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
}
