<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class SuperAdminController extends Controller
{
	public function show() {

    	return view(
            'superadmin',
            // compact(                    
            //     'projects',
            // )
        );
	}

	public function exportUsers() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
