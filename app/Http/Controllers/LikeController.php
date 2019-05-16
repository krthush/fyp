<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;
use App\Project;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
	// app/Http/Controllers/LikeController.php

    // public function likeObject($id)
    // {
    //     // here you can check if product exists or is valid or whatever

    //     $this->handleLike('App\Object', $id);
    //     return redirect()->back()->with('success', 'Object handled successfully.');
    // }

    public function like($id) {

        $type = 'App\Project';

        $project = Project::find($id);

        $existing_like = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($id)->whereUserId(Auth::id())->first();

        $max_like_order = Like::withTrashed()->whereLikeableType($type)->whereUserId(Auth::id())->max('order_column');


        if (is_null($existing_like)) {
            Like::create([
                'user_id'       => Auth::id(),
                'likeable_id'   => $id,
                'likeable_type' => $type,
                'order_column' => $max_like_order+1,
            ]);

            $project->update(['popularity'=> $project->likes->count() ]);

            return redirect()->back()->with('success', 'Project selected successfully.');
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
                $project->update(['popularity'=> $project->likes->count() ]);
                return redirect()->back()->with('success', 'Project deselected successfully.');
            } else {
                $existing_like->restore();
                $project->update(['popularity'=> $project->likes->count() ]);
                return redirect()->back()->with('success', 'Project selected successfully.');
            }
        }
    }

    public function handleLike($type, $id) {

        $existing_like = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($id)->whereUserId(Auth::id())->first();

        if (is_null($existing_like)) {
            Like::create([
                'user_id'       => Auth::id(),
                'likeable_id'   => $id,
                'likeable_type' => $type,
            ]);
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }
    }

    public function reorder (Request $request) {

        $type = 'App\Project';

        $projectOrder = request('projectOrder');

        $likes = Like::withTrashed()->whereLikeableType($type)->whereUserId(Auth::id())->get();

        for ($x = 0; $x < count($projectOrder); $x++) {
            Like::withTrashed()->whereLikeableType($type)->whereUserId(Auth::id())->whereLikeableId($projectOrder[$x])->update(['order_column' => $x+1]);
        }

    }

    public function rankup (Project $project) {

        $type = 'App\Project';

        $existing_like_1 = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($project->id)->whereUserId(Auth::id())->first();

        $existing_like_2 = Like::withTrashed()->whereLikeableType($type)->whereUserId(Auth::id())->where('order_column', '<', $existing_like_1->order_column)->orderBy('order_column', 'DESC')->first();

        if (is_null($existing_like_2)) {

            return back()->withErrors([
                'This project already has your highest rank'
            ]);

        } else {

            $new_like_order_1 = $existing_like_2->order_column;

            $new_like_order_2 = $existing_like_1->order_column;

            $existing_like_1->update([

                'order_column' => $new_like_order_1,

            ]);

            $existing_like_2->update([

                'order_column' => $new_like_order_2,

            ]);

            return back()->with('success', 'Rank up successfully.');

        }

    }

    public function rankdown (Project $project) {

        $type = 'App\Project';

        $existing_like_1 = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($project->id)->whereUserId(Auth::id())->first();

        $existing_like_2 = Like::withTrashed()->whereLikeableType($type)->whereUserId(Auth::id())->where('order_column', '>', $existing_like_1->order_column)->orderBy('order_column', 'ASC')->first();

        if (is_null($existing_like_2)) {

            return back()->withErrors([
                'This project already has your lowest rank'
            ]);

        } else {

            $new_like_order_1 = $existing_like_2->order_column;

            $new_like_order_2 = $existing_like_1->order_column;

            $existing_like_1->update([

                'order_column' => $new_like_order_1,

            ]);

            $existing_like_2->update([

                'order_column' => $new_like_order_2,

            ]);

            return back()->with('success', 'Rank up successfully.');

        }

    }

}
