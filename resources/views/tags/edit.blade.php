@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Edit Tag <small>{{ $tag->name }}</small></h1>
    </div>

    <form action="{{ url('tags/' . $tag->id) }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-sm-3 control-label">Name</label>

            <div class="col-sm-6">
                <input id="name" type="text" name="name" value="{{ old('name', $tag->name) }}" placeholder="Food" class="form-control">

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('transactions') ? ' has-error' : '' }}">
            <label for="transactions" class="col-sm-3 control-label">Transactions</label>

            <div class="col-sm-6">
                <select name="transactions[]" multiple class="form-control">
                    @foreach (App\Transaction::orderBy('date', 'desc')->get() as $transaction)
                    <option value="{{ $transaction->id }}"{{ in_array($transaction->id, old('transactions', $tag->transactions->pluck('id')->toArray())) ? ' selected' : '' }}>{{ $transaction->name . ' (' . $transaction->amount . ')' }}</option>
                    @endforeach
                </select>

                @if ($errors->has('transactions'))
                    <span class="help-block">
                        <strong>{{ $errors->first('transactions') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <a href="{{ url('tags') }}" class="btn btn-default">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-btn fa-pencil"></i> Edit Tag
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
