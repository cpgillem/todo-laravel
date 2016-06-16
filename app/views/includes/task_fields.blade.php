<script>
  $(function() {
    $("#datepicker_due_date").datepicker({
      dateFormat: "yy-mm-dd"
    });
  });

  $(function() {
    $("#spinner_due_time_hours").spinner({
      
    });
  });

  $(function() {
    $("#spinner_due_time_minutes").spinner();
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
  {{ Form::label('due_date', 'Due Date') }}
  {{ Form::text('due_date', null, ['id' => 'datepicker_due_date']) }}

  @if (isset($errors))
  <p class="help-text">{{ $errors->first('due_date') }}</p>
  @endif
</div>

<div class="row column">
  {{ Form::label('due_time', 'Due Time'); }}
  {{ Form::text('due_time', null, ['id' => 'spinner_due_time']) }}

  @if (isset($errors))
  <p class="help-text">{{ $errors->first('due_time') }}</p>
  @endif
</div>

<div class="row column">
  {{ Form::checkbox('done') }}
  {{ Form::label('done', 'Done') }}

  @if (isset($errors))
  <p class="help-text">{{ $errors->first('done') }}</p>
  @endif
</div>
