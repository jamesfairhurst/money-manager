@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <a href="{{ url('transactions/create') }}" class="btn btn-primary pull-right">
            <i class="fa fa-btn fa-plus"></i> Add Transaction
        </a>

        <a href="{{ url('transactions/import') }}" class="btn btn-default pull-right" style="margin-right: 5px;">
            <i class="fa fa-btn fa-upload"></i> Import Transactions
        </a>
        <h1>Transactions</h1>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Tags</th>
                <th>Date</th>
                <th>Amount</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @if (count($transactions) > 0)
            @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->name }}</td>
                <td>
                    @foreach ($transaction->tags as $tag)
                        <a href="{{ url('transactions?tag=' . $tag->id) }}" class="btn btn-xs btn-info">{{ $tag->name }}</a>
                    @endforeach
                </td>
                <td>{{ $transaction->date }}</td>
                <td>{{ $transaction->amount }}</td>
                <td class="text-right">
                    <a href="{{ url('transactions', [$transaction->id, 'edit']) }}" class="btn btn-default">Edit</a>
                    <form action="{{ url('transactions/' . $transaction->id) }}" method="POST" class="visible-xs-inline visible-sm-inline visible-md-inline visible-lg-inline" onclick="return confirm('Are you sure?')">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}

                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr class="info">
                <td colspan="3"></td>
                <td><strong>{{ $transactions->sum('amount') }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {!! $transactions->links() !!}
</div>
@endsection
