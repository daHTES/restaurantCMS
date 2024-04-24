@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <div class="row text-center">
                        @if(Auth::user()->checkAdmin())
                        <div class="col-sm-4">
                            <a href="/management">
                            <h4>Managment</h4>
                            <img width="50px" height="50px" src="{{asset('img/management.png')}}">
                            </a>
                        </div>
                        @endif
                        <div class="col-sm-4">
                            <a href="/cashier">
                            <h4>Cashier</h4>
                            <img width="50px" height="50px" src="{{asset('img/report.png')}}">
                            </a>
                        </div>
                            @if(Auth::user()->checkAdmin())
                        <div class="col-sm-4">
                            <a href="/report">
                            <h4>Analytics</h4>
                            <img width="50px" height="50px" src="{{asset('img/analytics.png')}}">
                            </a>
                        </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
