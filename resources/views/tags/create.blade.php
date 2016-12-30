@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Add Tag</h1>
    </div>

    <form action="{{ url('tags') }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-sm-3 control-label">Name</label>

            <div class="col-sm-6">
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Food" class="form-control">

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
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
                    <i class="fa fa-btn fa-plus"></i> Add Tag
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
