{{ Form::open(['method' => 'GET', 'route' => ['products.search']]) }}
{{-- https://stackoverflow.com/questions/10615872/bootstrap-align-input-with-button --}}
<div class="form-group card">
  <div class="card-body input-group">
  {{ Form::text('q', '', ['size'=>'100x1', 'style' => 'width: 100%', 'class'=> 'form-control']) }}

  {{ Form::submit('Search', array('class'=>'input-group-btn btn-info btn-lg')) }}
  {{ Form::close() }}
  </div>
</div>
