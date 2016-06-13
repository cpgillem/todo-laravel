<script>
  $(function() {
    $("#datepicker_due").datepicker({
      dateFormat: "yy-mm-dd"
    });
  });
</script>

<div class="row column">
  {{ Form::label('description', 'Description') }}
  {{ Form::text('description') }}

  @if (isset($errors))
  <p class="help-text">{{ $errors->first('description') }}</p>
  @endif
</div>

<div class="row column">
  {{ Form::label('due', 'Due') }}
  {{ Form::text('due', null, ['id' => 'datepicker_due']) }}

  @if (isset($errors))
  <p class="help-text">{{ $errors->first('due') }}</p>
  @endif
</div>

<div class="row column">
  {{ Form::checkbox('done') }}
  {{ Form::label('done', 'Done') }}

  @if (isset($errors))
  <p class="help-text">{{ $errors->first('done') }}</p>
  @endif
</div>
