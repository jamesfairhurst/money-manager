@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Import Transactions</h1>
    </div>

    @if (empty($csv))

        <form action="{{ url('transactions/import-file') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
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

    @else

        <div class="alert alert-info">
            <strong>Review the Transaction headings to identify the correct fields</strong>
        </div>

        <form action="{{ url('transactions/import-transactions') }}" method="POST">
            {{ csrf_field() }}

            <table class="table">
                <thead>
                    <tr>
                        @foreach ($csv[0] as $cell)
                        <th>{{ $cell }}</th>
                        @endforeach
                        <th>Import?</th>
                    </tr>
                    <tr>
                        @foreach ($csv[0] as $key => $cell)
                        <th>
                            <select name="types[{{ $key }}]" class="form-control">
                                <option value="">Type of field</option>
                                <option value="name"{{ in_array(old('types.' . $key, (isset($headingRows[$key]) && $headingRows[$key]) ? $headingRows[$key] : null), ['string', 'name']) ? ' selected' : '' }}>Name</option>
                                <option value="description"{{ (old('types.' . $key) == 'description') ? ' selected' : '' }}>Description</option>
                                <option value="date"{{ old('types.' . $key, (isset($headingRows[$key]) && $headingRows[$key]) ? $headingRows[$key] : null) == 'date' ? ' selected' : '' }}>Date</option>
                                <option value="amount"{{ in_array(old('types.' . $key, (isset($headingRows[$key]) && $headingRows[$key]) ? $headingRows[$key] : null), ['numeric', 'amount']) ? ' selected' : '' }}>Amount</option>
                            </select>
                        </th>
                        @endforeach
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (array_slice($csv, 1) as $key => $row)
                    <tr>
                        @foreach ($row as $cell)
                        <td>{{ $cell }}</td>
                        @endforeach
                        <td><input type="checkbox" name="rows[]" value="{{ $key+1 }}"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="form-group">
                <a href="{{ url('transactions/import?reset=1') }}" class="btn btn-default">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-btn fa-upload"></i> Import
                </button>
            </div>
        </form>

    @endif
</div>
@endsection
