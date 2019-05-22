<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Exports\ProjectsExport;
use App\Exports\SelectedProjectUsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Config;

class SuperAdminController extends Controller
{
	public function show() {

		$user = auth()->user();

		if ($user->superadmin == true) {

			$superadmin = true;
			$active_project_viewing = config('superadmin-settings.active_project_viewing');
			$active_project_selection = config('superadmin-settings.active_project_selection');
			$active_project_all_matching = config('superadmin-settings.active_project_all_matching');
			$active_project_first_matching = config('superadmin-settings.active_project_first_matching');

	    	return view(
	            	'superadmin',
		            compact(                    
		                'superadmin',
		                'active_project_viewing',
		                'active_project_selection',
		                'active_project_all_matching',
		                'active_project_first_matching',
		            )
	        );

		} else {
			return view('welcome')->withErrors([
                'You must be a superadmin to do this.'
            ]);
		}

	}

	public function exportUsers() {

        return Excel::download(new UsersExport, 'users.xlsx');
    }

   	public function exportProjects() {

        return Excel::download(new ProjectsExport, 'projects.xlsx');
    }

    public function exportSelectedProjectUsers() {

        return Excel::download(new SelectedProjectUsersExport, 'projects.xlsx');
    }

    public function toggleProjectViewing() {

    	$user = auth()->user();

		if ($user->superadmin == true) {
		
			if (config('superadmin-settings.active_project_viewing') == true) {
				$value = false;
			} else {
				$value = true;
			}

			Config::write('superadmin-settings.active_project_viewing', $value);

			return response()->json(array('success' => true));

		} else {
			return view('welcome')->withErrors([
                'You must be a superadmin to do this.'
            ]);
		}
    }

    public function toggleProjectSelection() {

    	$user = auth()->user();

		if ($user->superadmin == true) {
		
			if (config('superadmin-settings.active_project_selection') == true) {
				$value = false;
			} else {
				$value = true;
			}

			Config::write('superadmin-settings.active_project_selection', $value);

			return response()->json(array('success' => true));

		} else {
			return view('welcome')->withErrors([
                'You must be a superadmin to do this.'
            ]);
		}
    }

    public function toggleProjectFirstMatching() {

    	$user = auth()->user();

		if ($user->superadmin == true) {
		
			if (config('superadmin-settings.active_project_first_matching') == true) {
				$value = false;
			} else {
				$value = true;
			}

			Config::write('superadmin-settings.active_project_first_matching', $value);

			return response()->json(array('success' => true));

		} else {
			return view('welcome')->withErrors([
                'You must be a superadmin to do this.'
            ]);
		}
    }

    public function toggleProjectAllMatching() {

    	$user = auth()->user();

		if ($user->superadmin == true) {
		
			// Change setting for all matching
			if (config('superadmin-settings.active_project_all_matching') == true) {
				$value = false;
				//If true, toggling switch to false, so remember to stop 1st matching aswell
				Config::write('superadmin-settings.active_project_first_matching', false);
			} else {
				$value = true;
			}

			Config::write('superadmin-settings.active_project_all_matching', $value);

			return response()->json(array('success' => true));

		} else {
			return view('welcome')->withErrors([
                'You must be a superadmin to do this.'
            ]);
		}
    }

}
