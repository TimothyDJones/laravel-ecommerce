<?php //namespace ;

use Illuminate\Routing\Controller;

class TasksController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /tasks
	 *
         * @param Project $project
	 * @return Response
	 */
	public function index(Project $project)
	{
		//return View::make('tasks.index');
		$this->layout->content = View::make('tasks.index', compact('project'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tasks/create
	 *
         * @param Project $project
	 * @return Response
	 */
	public function create(Project $project)
	{
		//return View::make('tasks.create');
		$this->layout->content = View::make('tasks.create', compact('project'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tasks
	 *
         * @param Project $project
	 * @return Response
	 */
	public function store(Project $project)
	{
		$input = Input::all();
		$input['project_id'] = $project->id;
		$task = new Task($input);
                
                if ( $task->save() )
        		return Redirect::route('projects.show', $project->slug)->with('message', 'Task created.');
                else
                        return Redirect::route('projects.tasks.create', $project->slug)->withInput()->withErrors( $task->errors() );
	}

	/**
	 * Display the specified resource.
	 * GET /tasks/{id}
	 *
	 * @param  Project $project
         * @param  Task $task
	 * @return Response
	 */
	public function show(Project $project, Task $task)
	{
		//return View::make('tasks.show');
		$this->layout->content = View::make('tasks.show', compact('project', 'task'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /tasks/{id}/edit
	 *
	 * @param  Project $project, Task $task
	 * @return Response
	 */
	public function edit(Project $project, Task $task)
	{
		//return View::make('tasks.edit');
		$this->layout->content = View::make('tasks.edit', compact('project', 'task'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /tasks/{id}
	 *
	 * @param  Project $project, Task $task
	 * @return Response
	 */
	public function update(Project $project, Task $task)
	{
		$input = array_except(Input::all(), '_method');
		$task->fill($input);
                
                if ( $task->updateUniques() )
        		return Redirect::route('projects.tasks.show', [$project->slug, $task->slug])->with('message', 'Task updated.');
                else
                        return Redirect::route('projects.tasks.edit', [$project->slug, array_get($task->getOriginal(), 'slug')])->withInput()->withErrors( $task->errors() );
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /tasks/{id}
	 *
	 * @param  Project $project, Task $task
	 * @return Response
	 */
	public function destroy(Project $project, Task $task)
	{
		$task->delete();
                
                return Redirect::route('projects.show', $project->slug)->with('message', 'Task deleted.');
	}

}
