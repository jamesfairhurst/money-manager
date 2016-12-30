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
        $transaction = factory(App\Transaction::class)->create();

        $this->visit('/transactions/1/edit')
             ->type(50, 'amount')
             ->press('Edit Transaction')
             ->seePageIs('/transactions')
             ->see('Transaction updated!');
    }

    public function testDeleteTransaction()
    {
        $transaction = factory(App\Transaction::class)->create();

        $this->visit('/transactions')
             ->press('Delete')
             ->seePageIs('/transactions')
             ->see('Transaction deleted!');
    }
}
