{{ Form::open(['method' => 'GET', 'route' => ['products.search']]) }}
{{-- https://stackoverflow.com/questions/10615872/bootstrap-align-input-with-button --}}
<div class="form-group card">
  <div class="card-body">
    <div class="input-group">
      {{ Form::text('q', '', ['size'=>'100x1', 'class'=> 'form-control']) }}

      <span class="input-group-append input-group-btn">{{ Form::submit('Search', array('class'=>'btn btn-default')) }}</span>
    </div>
  {{ Form::close() }}
  </div>
</div>
