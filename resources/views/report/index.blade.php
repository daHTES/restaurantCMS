@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Main Functions</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Report</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <form action="/report/show" method="GET">
                <div class="col-md-12">
                    <label>Choose Date for Report</label>

                    <div class="input-group" id="datetimepicker1" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        <input id="datetimepicker1Input" name="dateStart" type="text" class="form-control bg-white" data-td-target="#datetimepicker1">
                        <span class="input-group-text" data-td-target="#datetimepicker1" data-td-toggle="datetimepicker">
                            📅
                        </span>
                    </div>

                    <div class="input-group" id="datetimepicker2" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        <input id="datetimepicker2Input" name="dateEnd" type="text" class="form-control bg-white" data-td-target="#datetimepicker2">
                        <span class="input-group-text" data-td-target="#datetimepicker2" data-td-toggle="datetimepicker">
                            📅
                        </span>
                    </div>
                    <input class="btn btn-primary my-2" type="submit" value="Show Report">
                </div>
            </form>
        </div>
    </div>

    <script  type="module">
        new TempusDominus(document.getElementById('datetimepicker1'), {
        });
        new TempusDominus(document.getElementById('datetimepicker2'), {
        });
    </script>

@endsection

