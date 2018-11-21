{{ Form::open(['method' => 'GET', 'route' => ['products.search']]) }}

<div class="form-group card">
  <div class="card-body">
  {{ Form::text('q', '', ['size'=>'100x1', 'style' => 'width: 100%']) }}

  {{ Form::submit('Search', array('class'=>'btn-info btn-lg')) }}
  {{ Form::close() }}
  </div>
</div>
