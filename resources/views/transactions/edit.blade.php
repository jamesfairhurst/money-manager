@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Edit Transaction <small>{{ $transaction->name }}</small></h1>
    </div>

    <form action="{{ url('transactions/' . $transaction->id) }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-sm-3 control-label">Name</label>

            <div class="col-sm-6">
                <input id="name" type="text" name="name" value="{{ old('name', $transaction->name) }}" placeholder="Food Shop" class="form-control">

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            <label for="description" class="col-sm-3 control-label">Description</label>

            <div class="col-sm-6">
                <textarea id="description" name="description" class="form-control">{{ old('description', $transaction->description) }}</textarea>

                @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
            <label for="date" class="col-sm-3 control-label">Date</label>

            <div class="col-sm-6">
                <input id="date" type="date" name="date" value="{{ old('date', $transaction->date) }}" class="form-control">

                @if ($errors->has('date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('date') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
            <label for="amount" class="col-sm-3 control-label">Amount</label>

            <div class="col-sm-6">
                <input id="amount" type="number" min="0" name="amount" value="{{ old('amount', $transaction->amount) }}" placeholder="50" class="form-control">

                @if ($errors->has('amount'))
                    <span class="help-block">
                        <strong>{{ $errors->first('amount') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <a href="{{ url('transactions') }}" class="btn btn-default">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-btn fa-pencil"></i> Edit Transaction
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
