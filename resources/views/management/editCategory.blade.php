@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('management.inc.sidebar')
            <div class="col-md-8">
                👉 Edit Category
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
                <form action="/management/category/{{$category->id}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <lable for="categoryName">Category Name</lable>
                        <input type="text" name="name" value="{{$category->name}}" class="form-control mt-2 bg-white" placeholder="Category...">
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Update</button>
                </form>
            </div>
        </div>
    </div>

@endsection
