@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h1>Products list</h1></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert {{ session('status-class') }}" role="alert">
                            {{ session('status') }}
                        </div>
                        {{ session()->forget('status') }}
                    @endif

                    <h1>Products</h1>
                    <ul>
                      @foreach ($products as $product)
                        <li><a href="{{ route('products.show', ['id' => $product->id] ) }}">{{ $product->title }}</a></li>
                      @endforeach
                    </ul>
                    {!! $products->links('pagination') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
