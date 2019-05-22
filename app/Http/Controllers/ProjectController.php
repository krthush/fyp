<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use App\Like;

class ProjectController extends Controller
{
    public function projects() {

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        $active_project_viewing = config('superadmin-settings.active_project_viewing');

        if ($active_project_viewing == 0) {

            return view('welcome')->withErrors([
                'Viewing all projects is currently shutdown'
            ]);

        } else {

            // default values for showing results
            $paginate = 5;
            $order = 'relevance';

            $search = "";

            $projects = Project::where('hidden', 0)->where('selected_user_id', 0)->where('selected_user2_id', 0)->paginate(5);

            $userProjects = Project::where('user_id',$userID)->get();
            $selectUserProjects = Project::where('user_id',$userID)->pluck('title','id')->all();
            $likedProjects = $user->likedProjects()->get();

            return view(
                    'projects',
                    compact(                    
                        'projects',
                        'userProjects',
                        'selectUserProjects',
                        'likedProjects',
                        'paginate',
                        'order',
                        'search'
                    )
                );
        }

    }

    public function search(Request $request) {

        $this->validate(request(), [
        ]);

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        $active_project_viewing = config('superadmin-settings.active_project_viewing');

        if ($active_project_viewing == 0) {

            return view('welcome')->withErrors([
                'Viewing all projects is currently shutdown'
            ]);

        } else {

            $paginate = request('paginate');
            $order = request('order');

            $search = $request->get('query');

            if (request('paginate') == 'all') {

                if (request('order') == 'name') {
                    $projects = Project::search($search)->where('hidden', 0)->within('orderByName')->where('selected_user_id', 0)->where('selected_user2_id', 0)->get();
                    // dd($projects);
                } else if (request('order') == 'author') {
                    $projects = Project::search($search)->where('hidden', 0)->within('orderByAuthor')->where('selected_user_id', 0)->where('selected_user2_id', 0)->get();
                } else if (request('order') == 'date') {
                    $projects = Project::search($search)->where('hidden', 0)->within('orderByDate')->where('selected_user_id', 0)->where('selected_user2_id', 0)->get();
                } else if (request('order') == 'popularity') {
                    $projects = Project::search($search)->where('hidden', 0)->within('orderByPopularity')->where('selected_user_id', 0)->where('selected_user2_id', 0)->get();
                } else {
                    $projects = Project::search($search)->where('hidden', 0)->where('selected_user_id', 0)->where('selected_user2_id', 0)->get();
                }

            } else {

                if (request('order') == 'name') {
                    $projects = Project::search($search)->where('hidden', 0)->within('orderByName')->where('selected_user_id', 0)->where('selected_user2_id', 0)->paginate(request('paginate'));
                    $projects->appends(['order' => 'name']);
                } else if (request('order') == 'author') {
                    $projects = Project::search($search)->where('hidden', 0)->within('orderByAuthor')->where('selected_user_id', 0)->where('selected_user2_id', 0)->paginate(request('paginate'));
                    $projects->appends(['order' => 'author']);
                } else if (request('order') == 'date') {
                    $projects = Project::search($search)->where('hidden', 0)->within('orderByDate')->where('selected_user_id', 0)->where('selected_user2_id', 0)->paginate(request('paginate'));
                    $projects->appends(['order' => 'date']);
                } else if (request('order') == 'popularity') {
                    $projects = Project::search($search)->where('hidden', 0)->within('orderByPopularity')->where('selected_user_id', 0)->where('selected_user2_id', 0)->paginate(request('paginate'));
                    $projects->appends(['order' => 'popularity']);
                } else {
                    $projects = Project::search($search)->where('hidden', 0)->where('selected_user_id', 0)->where('selected_user2_id', 0)->paginate(request('paginate'));
                }

                $projects->appends(['paginate' => request('paginate')]);

            }

            $userProjects = Project::where('user_id',$userID)->get();
            $selectUserProjects=Project::where('user_id',$userID)->pluck('title','id')->all();
            $likedProjects = $user->likedProjects()->get();

            // If there are results return them, if none, return the error message.
            if ($projects->count()) {

                return view(
                        'projects',
                        compact(                        
                            'projects',
                            'userProjects',
                            'selectUserProjects',
                            'likedProjects',
                            'paginate',
                            'order',
                            'search'
                        )
                    );

            } else {

                return view(
                    'projects',
                    compact(                    
                        'projects',
                        'userProjects',
                        'selectUserProjects',
                        'likedProjects',
                        'paginate',
                        'order',
                        'search'
                    )
                )->withErrors([
                    'No results found, please try with different keywords.'
                ]);
            }
        }
    }

    // Show specific project and its details
    public function show(Project $project) {

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        $active_project_viewing = config('superadmin-settings.active_project_viewing');

        if ($active_project_viewing == true || $project->user_id === $userID) {

            $usersLiked = $project->getUsersLiked;

            $likeables = Like::where('likeable_id',$project->id)->get();

            return view(
                'project',
                compact(                
                    'project',
                    'usersLiked',
                    'likeables'
                )
            );

        } else {

            return view('welcome')->withErrors([
                'Viewing all projects is currently shutdown'
            ]);
        }
    }

    public function dashboard() {

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        $userProjects = Project::where('user_id',$userID)->get();
        $selectUserProjects = Project::where('user_id',$userID)->pluck('title','id')->all();
        $likedProjects = $user->likedProjects()->get();

        return view(
                'dashboard',
                compact(                    
                    'userProjects',
                    'selectUserProjects',
                    'likedProjects'
                )
            );
    }

	public function store(Request $request) {

        $this->validate(request(), [
                'title' => 'required',
        ]);

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        if (request('UG') == "true") {
            $UG = 1;
        } else {
            $UG = 0;
        }

        if (request('MSc') == "true") {
            $MSc = 1;
        } else {
            $MSc = 0;
        }

        if (request('experimental') == "true") {
            $experimental = 1;
        } else {
            $experimental = 0;
        }

        if (request('computational') == "true") {
            $computational = 1;
        } else {
            $computational = 0;
        }

        if (request('hidden') == "true") {
            $hidden = 1;
        } else {
            $hidden = 0;
        }

        $popularity = 0;

        $project = Project::create([

            'user_id' => auth()->user()->getAuthIdentifier(),

            'title' => request('title'),

            'description' => request('description'),

            'UG' => $UG,

            'MSc' => $MSc,

            'experimental' => $experimental,

            'computational' => $computational,

            'hidden' => $hidden,

            'popularity' => $popularity,

            'selected_user_id' => 0,

            'selected_user2_id' => 0,

        ]);

        return redirect(route('dashboard'))->with('success', 'New Project added successfully.');

    }

    public function update(Project $project) {

        $this->validate(request(), [
                'title' => 'required'
        ]);

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        if (request('UG') == "true") {
            $UG = 1;
        } else {
            $UG = 0;
        }

        if (request('MSc') == "true") {
            $MSc = 1;
        } else {
            $MSc = 0;
        }

        if (request('experimental') == "true") {
            $experimental = 1;
        } else {
            $experimental = 0;
        }

        if (request('computational') == "true") {
            $computational = 1;
        } else {
            $computational = 0;
        }

        if (request('hidden') == "true") {
            $hidden = 1;
        } else {
            $hidden = 0;
        }

        $popularity = $project->likes->count();

        if ($project->user_id === $userID || $user->admin == 1) {

            Project::where('id', $project->id)->update([

                'title' => request('title'),

                'description' => request('description'),

                'UG' => $UG,

                'MSc' => $MSc,

                'experimental' => $experimental,

                'computational' => $computational,

                'hidden' => $hidden,

                'popularity' => $popularity,

            ]);

            return back()->with('success', 'Name edited successfully.');

        } else {
            return back()->withErrors([
                'You can only update your own projects.'
            ]);
        }

    }

    public function destroy(Request $request) {

        $customMessages = [
            'required' => 'Please select a project to delete.'
        ];

        $this->validate(request(), [
            'id' => 'required',
        ], $customMessages);

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        // project that is being deleted
        $project = Project::findOrFail(request('id'));


        if ($project->user_id === $userID || $user->admin == 1) {

            Project::where('id',request('id'))->delete();

            return redirect(route('dashboard'))->with('success', 'Project has been deleted');

        } else {

            return back()->withErrors([
                'You can only delete your own projects.'
            ]);

        }

    }

    // Match user to a project
    public function match(Project $project, $student_id) {

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        $student = User::find($student_id);

        if ($student->isSelected()) {

            return back()->withErrors([
                'This student has already been selected for another project.'
            ]);

        } else {

            if ($project->user_id === $userID) {

                if ($project->selected_user_id === 0) {

                    $project->update([

                            'selected_user_id' => $student_id,

                    ]);

                } else {

                    $project->update([

                            'selected_user2_id' => $student_id,

                    ]);

                }

                return back()->with('success', 'Student selected successfully.');

            } else {

                return back()->withErrors([
                    'You can only select students for your own projects.'
                ]);

            }

        }

    }

    // Unmatch user from project
    public function unmatch(Project $project, $student_id) {

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        if ($project->user_id === $userID) {


            if ($project->selected_user2_id == $student_id) {

                $project->update([

                        'selected_user2_id' => 0,

                ]);

            } else if ($project->selected_user_id == $student_id) {

                $project->update([

                        'selected_user_id' => 0,

                ]);

            }

            return back()->with('success', 'Student deselected successfully.');

        } else {

            return back()->withErrors([
                'You can only select students for your own projects.'
            ]);

        }

    }
}
