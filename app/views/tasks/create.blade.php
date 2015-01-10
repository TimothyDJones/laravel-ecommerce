@section('main')
	<h2>Create Task for Project "{{ $project->name }}"</h2>

	{{ Form::model(new Task, ['route' => ['projects.tasks.store', $project->slug]]) }}
		@include('tasks/partials/_form', ['submit_text' => 'Create Task'])
	{{ Form::close() }}
@stop
