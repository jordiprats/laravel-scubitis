@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h1>{{ __('Dashboard') }}</h1></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert {{ session('status-class') }}" role="alert">
                            {{ session('status') }}
                        </div>
                        {{ session()->forget('status') }}
                    @endif

                    <center>
                      <iframe width="560" height="315" src="https://www.youtube.com/embed/DjO7U7az88s" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </center>

                    <hr />

                    {{ Form::open(['method' => 'GET', 'route' => ['products.search']]) }}

                    <div class="form-group">
                      {{ Form::label('q', 'Search products') }}
                      {{ Form::text('q', '', ['size'=>'100x1']) }}
                    </div>

                    {{ Form::submit('Search', array('class'=>'btn-info btn-lg')) }}
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
