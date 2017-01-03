@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Import Transactions</h1>
    </div>

    <form action="{{ url('transactions/import') }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('csv') ? ' has-error' : '' }}">
            <label for="csv" class="col-sm-3 control-label">CSV File</label>

            <div class="col-sm-6">
                <input id="csv" type="file" name="csv">

                @if ($errors->has('csv'))
                    <span class="help-block">
                        <strong>{{ $errors->first('csv') }}</strong>
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
                    <i class="fa fa-btn fa-upload"></i> Import
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
