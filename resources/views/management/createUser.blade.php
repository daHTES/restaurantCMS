@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('management.inc.sidebar')
            <div class="col-md-8">
                👨 Create a User
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
                <form action="/management/user" method="POST">
                    @csrf
                    <div class="form-group">
                        <lable for="name">Name</lable>
                        <input type="text" name="name" class="form-control mt-2 bg-white" placeholder="Name...">
                    </div>
                    <div class="form-group">
                        <lable for="email">Email</lable>
                        <input type="email" name="email" class="form-control mt-2 bg-white" placeholder="Email...">
                    </div>
                    <div class="form-group">
                        <lable for="email">Password</lable>
                        <input type="password" name="password" class="form-control mt-2 bg-white" placeholder="Password...">
                    </div>
                    <div class="form-group">
                        <lable for="Role">Role</lable>
                        <select name="role" class="form-control bg-white">
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
        </div>
    </div>

@endsection
