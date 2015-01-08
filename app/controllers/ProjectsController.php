<?php //namespace ;

use Illuminate\Routing\Controller;

class ProjectsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /projects
	 *
	 * @return Response
	 */
	public function index()
	{
		$projects = Project::all();
		//return View::make('projects.index');
		$this->layout->content = View::make('projects.index', compact('projects'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /projects/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//return View::make('projects.create');
		$this->layout->content = View::make('projects.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /projects
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
                $project = new Project($input);
                
                if ( $project->save() )
        		return Redirect::route('projects.index')->with('message', 'Project created.');
                else
                        return Redirect::route('projects.create')->withInput()->withErrors( $project->errors() );
	}

	/**
	 * Display the specified resource.
	 * GET /projects/{id}
	 *
	 * @param  Project $project
	 * @return Response
	 */
	public function show(Project $project)
	{
		//return View::make('projects.show');
		$this->layout->content = View::make('projects.show', compact('project'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /projects/{id}/edit
	 *
	 * @param  Project $project
	 * @return Response
	 */
	public function edit(Project $project)
	{
		//return View::make('projects.edit');
		$this->layout->content = View::make('projects.edit', compact('project'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /projects/{id}
	 *
	 * @param  Project $project
	 * @return Response
	 */
	public function update(Project $project)
	{
		$input = array_except(Input::all(), '_method');
		$project->fill($input);
                
                if ( $project->updateUniques() )
        		return Redirect::route('projects.show', $project->slug)->with('message', 'Project updated.');
                else
                        return Redirect::route('projects.edit', array_get($project->getOriginal(), 'slug'))->withInput()->withErrors( $project->errors() );
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /projects/{id}
	 *
	 * @param  Project $project
	 * @return Response
	 */
	public function destroy(Project $project)
	{
		$project->delete();

		return Redirect::route('projects.index')->with('message', 'Project deleted.');
	}

}
