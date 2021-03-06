<?php

namespace App\Http\Controllers;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Csv\Reader;
use Storage;
use Exception;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with('tags')->orderBy('date', 'desc');

        if ($request->has('tag')) {
            $transactions->whereHas('tags', function ($query) use ($request) {
                $query->where('tags.id', $request->input('tag'));
            });
        }

        return view('transactions.index', [
            'transactions' => $transactions->paginate()
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

    /**
     * Display an import form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $csv = [];
        $headingRows = [];
        $duplicates = [];

        if ($request->has('reset')) {
            Storage::delete($request->session()->get('csv'));
            $request->session()->forget('csv');

            return redirect('transactions/import');
        }

        if ($request->session()->has('csv')) {
            $csv = Reader::createFromPath(storage_path('app/' . $request->session()->get('csv')))->fetchAll();

            // Get max cell count
            $maxCellCount = collect($csv)->map(function ($item, $key) {
                return count($item);
            })->max();

            // Pad array
            $csv = collect($csv)->map(function ($item, $key) use ($maxCellCount) {
                if (count($item) != $maxCellCount) {
                    $item = array_pad($item, $maxCellCount, '');
                }

                return $item;
            })->toArray();

            // Try to determine what the cells are
            foreach ($csv[1] as $key => $cell) {
                $cell = trim($cell);

                // Numeric?
                if (is_numeric($cell)) {
                    $headingRows[$key] = 'numeric';

                // Empty?
                } elseif (empty($cell)) {
                    $headingRows[$key] = 'empty';

                } else {
                    // Is this a date cell?
                    try {
                        $date = Carbon::parse($cell);
                        $headingRows[$key] = 'date';
                    } catch (Exception $e) {
                        // String?
                        if (is_string($cell)) {
                            $headingRows[$key] = 'string';

                        // Doubt this is needed
                        } else {
                            $headingRows[$key] = 'unknown';
                        }
                    }
                }
            }

            // Look up duplicates
            $dateKey = collect($headingRows)->search('date');
            $numericKey = collect($headingRows)->search('numeric');

            foreach (array_slice($csv, 1) as $key => $cell) {
                $duplicates[$key+1] = Transaction::where([
                    'date' => Carbon::parse($cell[$dateKey])->format('Y-m-d'),
                    'amount' => $cell[$numericKey]]
                )->first();
            }
        }

        return view('transactions.import', ['csv' => $csv, 'headingRows' => $headingRows, 'duplicates' => $duplicates]);
    }

    /**
     * Deal with the import file form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postImportFile(Request $request)
    {
        $this->validate($request, [
            'csv' => 'file|mimes:csv,txt'
        ]);

        // Move uploaded file to storage and save filename in session
        if ($request->file('csv')->isValid()) {
            $path = $request->csv->store('imported');

            session(['csv' => $path]);
        }

        return redirect('transactions/import')
            ->withSuccess('Transactions file imported, please review!');
    }

    /**
     * Deal with the import transactions form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postImportTransactions(Request $request)
    {
        if (!$request->session()->has('csv')) {
            return redirect('transactions/import')
                ->withInput()
                ->withDanger('CSV not uploaded!');
        }

        if (!$request->input('rows')) {
            return redirect('transactions/import')
                ->withInput()
                ->withDanger('Select transactions to import!');
        }

        // Get field types
        $name = array_search('name', $request->input('types'));
        $description = array_search('description', $request->input('types'));
        $date = array_search('date', $request->input('types'));
        $amount = array_search('amount', $request->input('types'));

        if (!($name !== false && $date !== false && $amount !== false)) {
            return redirect('transactions/import')
                ->withInput()
                ->withDanger('The name, date and amount fields need to be identified, please select them to import');
        }

        $csv = Reader::createFromPath(storage_path('app/' . $request->session()->get('csv')))->fetchAll();

        $checkedRows = collect($csv)->only($request->input('rows'));

        foreach ($checkedRows as $key => $row) {
            $transaction = Transaction::create([
                'name' => $row[$name],
                'description' => (isset($row[$description])) ? $row[$description] : null,
                'date' => Carbon::parse($row[$date])->format('Y-m-d'),
                'amount' => $row[$amount],
            ]);

            if (!empty($request->input('tags.' . $key))) {
                $transaction->saveTags($request->input('tags.' . $key));
            }
        }

        Storage::delete($request->session()->get('csv'));
        $request->session()->forget('csv');

        return redirect('transactions')
            ->withSuccess('Transactions imported successfully');
    }
}
