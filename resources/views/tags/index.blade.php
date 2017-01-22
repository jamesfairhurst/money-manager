@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <a href="{{ url('tags/create') }}" class="btn btn-primary pull-right">
            <i class="fa fa-btn fa-plus"></i> Add Tag
        </a>
        <h1>Tags</h1>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @if (count($tags) > 0)
            @foreach ($tags as $tag)
            <tr>
                <td>{{ $tag->name }}</td>
                <td class="text-right">
                    <a href="{{ url('tags', [$tag->id, 'edit']) }}" class="btn btn-default">Edit</a>
                    <form action="{{ url('tags/' . $tag->id) }}" method="POST" class="visible-xs-inline visible-sm-inline visible-md-inline visible-lg-inline" onclick="return confirm('Are you sure?')">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}

                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

    {!! $tags->links() !!}
</div>
@endsection
