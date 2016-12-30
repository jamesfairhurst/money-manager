<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
        $transactions = factory(App\Transaction::class, 20)->create();

        $this->visit('/transactions')
             ->see($transactions->first()->name);
    }
}
