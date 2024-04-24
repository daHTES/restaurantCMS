@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('management.inc.sidebar')
            <div class="col-md-8">
                🍔 Create a Menu
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
                <form action="/management/menu" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <lable for="menuName">Menu name</lable>
                        <input type="text" name="name" class="form-control mt-2 bg-white" placeholder="Menu...">
                    </div>
                    <label for="menuPrice" class="mt-2">Price</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="text" name="price" class="form-control" aria-label="Amount(in dollars)">
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
                        <input type="text" name="description" class="form-control bg-white"  placeholder="Description...">
                    </div>

                    <div class="form-group">
                        <label for="Category">Category</label>
                        <select name="category_id" class="form-control bg-white">
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{ $category->name }}</option>

                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
        </div>
    </div>

@endsection
