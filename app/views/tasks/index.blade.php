@section('main')
	/vagrant/laravel/resources/views/tasks/index.blade.php
	<h2>Tasks for Project {{ $project->name }}</h2>
	@if ( !$project->tasks->count() )
		No tasks!
	@else
		<ul>
			@foreach ( $project->tasks as $task )
				<li>
					<a href="{{ route('projects.tasks.show', array($project->slug, $task->slug)) }}">{{ $task->name }}</a>
					(
						{{ Form::open(array('class' => 'inline', 'method' => 'DELETE', 'route' => array('projects.tasks.destroy', $task->slug))) }}
							{{ link_to_route('projects.tasks.edit', 'Edit', array($project-> slug, $task->slug), array('class' => 'btn btn-info')) }},
							{{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
						{{ Form::close() }}
					)
				</li>
			@endforeach
		</ul>
	@endif
        
        <p>{{ link_to_route('projects.tasks.create', 'Create Task', array($project->slug)) }}</p>
	
	<p>{{ link_to_route('projects.create', 'Create Project') }}</p>        
@stop