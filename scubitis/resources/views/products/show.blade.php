@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h1>{{ $product->title }}</h1></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert {{ session('status-class') }}" role="alert">
                            {{ session('status') }}
                        </div>
                        {{ session()->forget('status') }}
                    @endif
                    <h2>Description</h2>
                    {{ $product->description }}

                    <h2>Prices</h2>
                    <ul>
                      <li>Current minimun price: {{ $product->currentminprice->price }} by {{ $product->currentminprice->website }} on {{ $product->currentminprice->data }}</li>
                      <li>Minimun price: {{ $product->minprice->price }} by {{ $product->minprice->website }} on {{ $product->minprice->data }}</li>
                      <li>Maximum price: {{ $product->maxprice->price }} by {{ $product->maxprice->website }} on {{ $product->maxprice->data }}</li>
                      <li>Average price: {{ $product->averageprice }}</li>
                      {{-- on <a href="{{ $product->minprice->url }}">{{ $product->minprice->website }}</a> - {{ $product->minprice->data }} --}}
                    </ul>
                    <div>
                      {!! $chart->container() !!}
                    </div>
                    {{-- <img src="{{ $product->image_url }}" /> --}}

                    @if($product->image_url!=null)
                    <img class="img-rounded img-thumbnail" src="{{ $product->image_url }}" />
                    <hr />
                    @endif



                </div>
            </div>
        </div>
    </div>
</div>
{!! $chart->script() !!}
@endsection
