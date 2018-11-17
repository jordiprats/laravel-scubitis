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
                      <li>Current minimun price: {{ $product->currentminwebprice->price }} by <a href="{{ $product->currentminwebprice->url }}">{{ $product->currentminwebprice->website }}</a> on {{ $product->currentminwebprice->data }}</li>
                      <li>Minimun price: {{ $product->minwebprice->price }} by <a href="{{ $product->minwebprice->url }}">{{ $product->minwebprice->website }}</a> on {{ $product->minwebprice->data }}</li>
                      <li>Maximum price: {{ $product->maxwebprice->price }} by <a href="{{ $product->maxwebprice->url }}">{{ $product->maxwebprice->website }}</a> on {{ $product->maxwebprice->data }}</li>
                      <li>Average price: {{ $product->averageprice }}</li>
                      {{-- on <a href="{{ $product->minwebprice->url }}">{{ $product->minwebprice->website }}</a> - {{ $product->minwebprice->data }} --}}
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
