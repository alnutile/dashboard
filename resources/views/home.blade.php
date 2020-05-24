@extends('layouts.app')

@section('content')
<div class="container">
    <div class="rows">
        <div class="col-md-12">
            <add-epic></add-epic>
        </div>
    </div>
    <div class="rows">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
