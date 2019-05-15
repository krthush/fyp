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

        $projects = Project::where('hidden',false)->get();
        $userProjects = Project::where('user_id',$userID)->get();
        $selectUserProjects = Project::where('user_id',$userID)->pluck('title','id')->all();
        $likedProjects = $user->likedProjects()->get();

        return view(
                'projects',
                compact(                    
                    'projects',
                    'userProjects',
                    'selectUserProjects',
                    'likedProjects'
                )
            );

    }

    public function search(Request $request) {

        $this->validate(request(), [
            'order' => 'required',
        ]);

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        $search = "";

        // Making sure the user entered a keyword.
        if($request->has('search')) {

            $search = $request->get('search');

            if (request('order') == 'name') {
                $projects = Project::search($search)->where('hidden', 0)->get();
                $projects = $projects->sortBy('title');
            } else if (request('order') == 'author') {
                $projects = Project::search($search)->within('orderByAuthor')->where('hidden', 0)->get();
            } else if (request('order') == 'date') {
                $projects = Project::search($search)->where('hidden', 0)->get();
                $projects = $projects->sortByDesc('updated_at');
            } else {
                $projects = Project::search($search)->where('hidden', 0)->get();
            }

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
                        'likedProjects'
                    )
                );

        } else {

            return view(
                'projects',
                compact(                    
                    'projects',
                    'userProjects',
                    'selectUserProjects',
                    'likedProjects'
                )
            )->withErrors([
                'No results found, please try with different keywords.'
            ]);
        }
    }

    // Show specific project and its details
    public function show(Project $project) {

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        $usersLiked = $project->likes;

        $likeables = Like::where('likeable_id',$project->id)->get();

        return view(
            'project',
            compact(                
                'project',
                'usersLiked',
                'likeables'
            )
        );
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
            $UG = true;
        } else {
            $UG = false;
        }

        if (request('MSc') == "true") {
            $MSc = true;
        } else {
            $MSc = false;
        }

        if (request('ME4') == "true") {
            $ME4 = true;
        } else {
            $ME4 = false;
        }

        if (request('experimental') == "true") {
            $experimental = true;
        } else {
            $experimental = false;
        }

        if (request('computational') == "true") {
            $computational = true;
        } else {
            $computational = false;
        }

        if (request('hidden') == "true") {
            $hidden = true;
        } else {
            $hidden = false;
        }

        $project = Project::create([

            'user_id' => auth()->user()->getAuthIdentifier(),

            'title' => request('title'),

            'description' => request('description'),

            'UG' => $UG,

            'MSc' => $MSc,

            'ME4' => $ME4,

            'experimental' => $experimental,

            'computational' => $computational,

            'hidden' => $hidden,


        ]);

        return redirect(route('projects'))->with('success', 'New Project added successfully.');

    }

    public function update(Project $project) {

        $this->validate(request(), [
                'title' => 'required'
        ]);

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        if (request('UG') == "true") {
            $UG = true;
        } else {
            $UG = false;
        }

        if (request('MSc') == "true") {
            $MSc = true;
        } else {
            $MSc = false;
        }

        if (request('ME4') == "true") {
            $ME4 = true;
        } else {
            $ME4 = false;
        }

        if (request('experimental') == "true") {
            $experimental = true;
        } else {
            $experimental = false;
        }

        if (request('computational') == "true") {
            $computational = true;
        } else {
            $computational = false;
        }

        if (request('hidden') == "true") {
            $hidden = true;
        } else {
            $hidden = false;
        }

        if ($project->user_id === $userID) {

            Project::where('id', $project->id)->update([

                'title' => request('title'),

                'description' => request('description'),

                'UG' => $UG,

                'MSc' => $MSc,

                'ME4' => $ME4,

                'experimental' => $experimental,

                'computational' => $computational,

                'hidden' => $hidden,

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


        if ($project->user_id === $userID) {

            Project::where('id',request('id'))->delete();

            return redirect(route('projects'))->with('success', 'Project has been deleted');

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

        if ($project->user_id === $userID) {

            if ($project->selected_user_id === null) {

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

    // Unmatch user from project
    public function unmatch(Project $project, $student_id) {

        $user = auth()->user();
        $userID = $user->getAuthIdentifier();

        if ($project->user_id === $userID) {


            if ($project->selected_user2_id == $student_id) {

                $project->update([

                        'selected_user2_id' => null,

                ]);

            } else if ($project->selected_user_id == $student_id) {

                $project->update([

                        'selected_user_id' => null,

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
