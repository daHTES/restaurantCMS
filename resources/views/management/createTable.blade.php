@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('management.inc.sidebar')
            <div class="col-md-8">
                🪑 Create a Table
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
                <form action="/management/table" method="POST">
                    @csrf
                    <div class="form-group">
                        <lable for="tableName">Table Name</lable>
                        <input type="text" name="name" class="form-control mt-2 bg-white" placeholder="Table...">
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
        </div>
    </div>

@endsection
