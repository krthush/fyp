<?php

namespace App\Exports;

use App\Project;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class SelectedProjectUsersExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'student',
            'title',
            'supervisor'
        ];
    }

    public function collection()
    {
    	$selectedProjects = Project::where('selected_user_id', '!=', 0)->get();

    	$selectedProjects2 = Project::where('selected_user2_id', '!=', 0)->get();

    	$collection = collect([]);

    	// Loop to check through 1st student selected projects
    	foreach ($selectedProjects as $selectedProject) {

    		$selectedUser = User::find($selectedProject->selected_user_id);

    		$supervisor = $selectedProject->user;

    		$collection->push(['student' => $selectedUser->email, 'title' => $selectedProject->title, 'supervisor' => $supervisor->email]);

    	}

    	// Loop to check through 2nd student selected projects
    	foreach ($selectedProjects2 as $selectedProject) {

    		$selectedUser = User::find($selectedProject->selected_user2_id);

    		$supervisor = $selectedProject->user;

			$collection->push(['student' => $selectedUser->email, 'title' => $selectedProject->title, 'supervisor' => $supervisor->email]);

    	}

    	// dd($collection);

        return $collection;
    }
}
