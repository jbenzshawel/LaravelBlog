@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                 	<p>Hello {{ $user->name }}</p>
                 	<p>Your email: {{ $user->email }}</p>
                 	<p>Last updated: {{ $lastUpdated }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
