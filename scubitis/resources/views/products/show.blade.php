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

                    <h2>Prices</h2>
                    <ul>
                      <li>Minimun price: {{ $product->minprice }}</li>
                      <li>Maximum price: {{ $product->maxprice }}</li>
                      <li>Average price: {{ $product->averageprice }}</li>
                      {{-- on <a href="{{ $product->minprice->url }}">{{ $product->minprice->website }}</a> - {{ $product->minprice->data }} --}}
                    </ul>

                    <h2>Description</h2>
                    {{ $product->description }}
                    
                    @if($product->image_url!=null)
                    <img class="img-rounded img-thumbnail" src="{{ $product->image_url }}" />
                    <hr />
                    @endif



                </div>
            </div>
        </div>
    </div>
</div>
@endsection
