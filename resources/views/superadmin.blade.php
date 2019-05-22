@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5>Super Admin Panel</h5></div>

                <div class="card-body">

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><strong>{{ $error }}</strong></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-10 align-self-center">
                            Toggle setting to allow viewing of projects:
                        </div>
                        <div class="col-2 d-flex flex-row-reverse">
                            @if($active_project_viewing == true)
                                <input id="toggle-project-viewing" type="checkbox" data-toggle="toggle" data-size="sm" checked>
                            @else
                                <input id="toggle-project-viewing" type="checkbox" data-toggle="toggle" data-size="sm">
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-10 align-self-center">
                            Toggle setting to allow selection and ranking of projects by students:
                        </div>
                        <div class="col-2 d-flex flex-row-reverse">
                            @if($active_project_selection == true)
                                <input id="toggle-project-selection" type="checkbox" data-toggle="toggle" data-size="sm" checked>
                            @else
                                <input id="toggle-project-selection" type="checkbox" data-toggle="toggle" data-size="sm">
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-10 align-self-center">
                            Toggle setting to allow staff matching of projects to students:
                        </div>
                        <div class="col-2 d-flex flex-row-reverse">
                            @if($active_project_all_matching == true)
                                <input id="toggle-project-all-matching" type="checkbox" data-toggle="toggle" data-size="sm" checked>
                            @else
                                <input id="toggle-project-all-matching" type="checkbox" data-toggle="toggle" data-size="sm">
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-10 align-self-center">
                            Toggle setting to limit staff matching of projects to <b>1st rank students only</b>:
                        </div>
                        <div class="col-2 d-flex flex-row-reverse">
                            @if($active_project_all_matching == true)
                                @if($active_project_first_matching == true)
                                    <input id="toggle-project-first-matching" type="checkbox" data-toggle="toggle" data-size="sm" checked>
                                @else
                                    <input id="toggle-project-first-matching" type="checkbox" data-toggle="toggle" data-size="sm">
                                @endif
                            @else
                                @if($active_project_first_matching == true)
                                    <input id="toggle-project-first-matching" type="checkbox" data-toggle="toggle" data-size="sm" checked disabled>
                                @else
                                    <input id="toggle-project-first-matching" type="checkbox" data-toggle="toggle" data-size="sm" disabled>
                                @endif
                            @endif
                        </div>
                    </div>

                    <hr>

                    The following buttons allow for downloading of excel spreadsheets of relevant FYP selection data:<br>

                    <!-- Button Trigger -->
                    <a href="{{ route('export-users') }}"><button type="button" class="btn btn-outline-secondary mt-3">Export Users</button></a>

                    <!-- Button Trigger -->
                    <a href="{{ route('export-projects') }}"><button type="button" class="btn btn-outline-secondary mt-3">Export Projects</button></a>

                    <!-- Button Trigger -->
                    <a href="{{ route('export-selected-project-users') }}"><button type="button" class="btn btn-outline-secondary mt-3">Export Selected Project Users</button></a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
