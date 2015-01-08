<ul>
  <li>
    {{ Form::label('name', 'Name:') }}
    {{ Form::text('name') }}
  </li>
  <li>
    {{ Form::label('completed', 'Completed:') }}
    {{ Form::checkbox('completed') }}
  </li>
  <li>
    {{ Form::label('description', 'Description:') }}
    {{ Form::textarea('description') }}
  </li>
  <li>
    {{ Form::submit($submit_text) }}
  </li>
</ul>
