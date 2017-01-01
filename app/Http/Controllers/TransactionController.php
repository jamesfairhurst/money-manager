<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transactions.index', [
            'transactions' => Transaction::orderBy('date', 'desc')->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        $transaction = Transaction::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'amount' => $request->amount,
        ]);

        if (!empty($request->tags)) {
            $transaction->saveTags($request->tags);
        }

        return redirect('transactions')
            ->withSuccess('Transaction added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', ['transaction' => $transaction]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->validate($request, [
            'name' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        $transaction->fill($request->all());
        $transaction->save();

        if (!empty($request->tags)) {
            $transaction->saveTags($request->tags);
        }

        return redirect('transactions')
            ->withSuccess('Transaction updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect('transactions')
            ->withSuccess('Transaction deleted!');
    }
}
