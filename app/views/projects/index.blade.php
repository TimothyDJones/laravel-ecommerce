@section('main')
	// /vagrant/laravel/resources/views/projects/index.blade.php
	<h2>Projects</h2>
	@if ( !$projects->count() )
		No projects!
	@else
		<ul>
			@foreach ( $projects as $project )
				<li>
					<a href="{{ route('projects.show', $project->slug) }}">{{ $project->name }}</a>
					(
						{{ Form::open(array('class' => 'inline', 'method' => 'DELETE', 'route' => array('projects.destroy', $project->slug))) }}
							{{ link_to_route('projects.edit', 'Edit', array($project->slug), array('class' => 'btn btn-info')) }},
							{{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
						{{ Form::close() }}
					)
				</li>
			@endforeach
		</ul>
	@endif
	
	<p>{{ link_to_route('projects.create', 'Create Project') }}</p>
@stop
