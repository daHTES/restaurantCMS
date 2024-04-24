@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('management.inc.sidebar')
            <div class="col-md-8">
                🍔 Edit a Menu
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
                <form action="/management/menu/{{$menu->id}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <lable for="menuName">Menu name</lable>
                        <input type="text" name="name" value="{{$menu->name}}" class="form-control mt-2 bg-white" placeholder="Menu...">
                    </div>
                    <label for="menuPrice" class="mt-2">Price</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="text" name="price" value="{{$menu->price}}" class="form-control" aria-label="Amount(in dollars)">
                        <div class="input-group-append">
                            <span class="input-group-text">.00</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="inputGroupFile01" class="form-label">Download Image</label>
                        <input name="image" class="form-control bg-white"  type="file" id="inputGroupFile01">
                    </div>

{{--                    <label for="MenuImage">Image</label>--}}
{{--                    <div class="mb-3">--}}
{{--                        <div class="input-group-prepend">--}}
{{--                            <span class="input-group-text">Upload</span>--}}
{{--                        </div>--}}
{{--                        <div class="custom-file">--}}
{{--                            <input type="file" name="image" class="custom-file-input" id="inputGroupFile01">--}}
{{--                            <label class="custom-file-label" for="inputGroupFile01">Choose File</label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group">
                        <label for="Description">Description</label>
                        <input type="text" name="description" value="{{$menu->description}}" class="form-control bg-white"  placeholder="Description...">
                    </div>

                    <div class="form-group mt-2">
                        <label for="Category">Category</label>
                        <select name="category_id" class="form-control bg-white">
                            @foreach($categories as $category)
                            <option value="{{$category->id}}" {{$menu->category_id === $category->id ? 'selected' : ''}}>{{ $category->name }}</option>

                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning mt-2">Edit</button>
                </form>
            </div>
        </div>
    </div>

@endsection
