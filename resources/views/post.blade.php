@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"></div>

                    <div class="panel-body posts">
                        <h2>{{ $post->title }}</h2>
                        <p>{{ $post->content }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection