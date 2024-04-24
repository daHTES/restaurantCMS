@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('management.inc.sidebar')
            <div class="col-md-8">
                🪑 Edit a Table
                <hr>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                           @foreach($errors->all() as $error)
                             <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="/management/table/{{$table->id}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <lable for="tableName">Table Name</lable>
                        <input type="text" name="name" value="{{$table->name}}" class="form-control mt-2 bg-white" placeholder="Table...">
                    </div>
                    <button type="submit" class="btn btn-warning mt-2">Edit</button>
                </form>
            </div>
        </div>
    </div>

@endsection
