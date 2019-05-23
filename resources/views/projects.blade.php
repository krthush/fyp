@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Project Page                    
                </div>

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

                    <form id="searchProjectForm" name="searchProjectForm" method="GET" action="{{ route('search-projects') }}">
                        <div class="form-group">
                            <div class="input-group input-group-md">
                                @if($search != "")
                                    <input class="form-control" type="text" value="{{ $search }}" name="query">
                                @else
                                    <input class="form-control" type="text" placeholder="What are you looking for?" name="query">
                                @endif
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" onclick="this.disabled=true;this.value='Submitting...'; this.form.submit();">Search</button>
                                </div>
                            </div>
                            <div class="input-group input-group-md mt-2">
                                <span style="display: flex; align-items: center;">
                                    Displayed projects:
                                    @if($paginate != "all")
                                        {{ $projects->total() }}
                                    @else
                                        {{ $projects->count() }}
                                    @endif
                                </span>
                                <div style="margin-left:auto; margin-right:0;">
                                    <select class="btn btn-outline-secondary" name="paginate">

                                        @if($paginate == 5)
                                            <option value="5" selected>5 Results</option>
                                        @else
                                            <option value="5">5 Results</option>
                                        @endif

                                        @if($paginate == 25)
                                            <option value="25" selected>25 Results</option>
                                        @else
                                            <option value="25">25 Results</option>
                                        @endif

                                        @if($paginate == 50)
                                            <option value="50" selected>50 Results</option>
                                        @else
                                            <option value="50">50 Results</option>
                                        @endif

                                        @if($paginate == "all")
                                            <option value="all" selected>Show All</option>
                                        @else
                                            <option value="all">Show All</option>
                                        @endif
                                        
                                    </select>
                                    <select class="btn btn-outline-secondary" name="order">

                                        @if($order == "projects")
                                            <option value="projects" selected>Order by Relevance</option>
                                        @else
                                            <option value="projects">Order by Relevance</option>
                                        @endif

                                        @if($order == "orderByName")
                                            <option value="orderByName" selected>Order by Name</option>
                                        @else
                                            <option value="orderByName">Order by Name</option>
                                        @endif

                                        @if($order == "orderByAuthor")
                                            <option value="orderByAuthor" selected>Order by Author</option>
                                        @else
                                            <option value="orderByAuthor">Order by Author</option>
                                        @endif

                                        @if($order == "orderByDate")
                                            <option value="orderByDate" selected>Order by Date</option>
                                        @else
                                            <option value="orderByDate">Order by Date</option>
                                        @endif

                                        @if($order == "orderByPopularity")
                                            <option value="orderByPopularity" selected>Order by Popularity</option>
                                        @else
                                            <option value="orderByPopularity">Order by Popularity</option>
                                        @endif

                                    </select>
                                    <button id="target" type="button" class="btn btn-light btn-outline-secondary">
                                        <i class="fa fa-sort" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="allprojects" class="list-group my-3">
                        @foreach($projects as $project)
                            <!-- <a href="{{ route('project', $project->id) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                  <h6 class="mb-1">{{ $project->title }}</h6>
                                    <small class="ml-5">{{ \Carbon\Carbon::parse($project->updated_at)->format('d/m/Y') }}</small>
                                </div>
                                <small>by {{ $project->user()->first()->name }}
                                        <span class="badge badge-primary badge-pill float-right mt-2">{{ $project->likes->count() }}</span>
                                </small>
                            </a> -->
                            <a href="#project_modal_{{ $project->id }}" data-toggle="modal" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                  <h6 class="mb-1">{{ $project->title }}</h6>
                                    <small class="ml-5">{{ \Carbon\Carbon::parse($project->updated_at)->format('d/m/Y') }}</small>
                                </div>
                                <small>by {{ $project->user()->first()->name }}
                                        <span class="badge badge-primary badge-pill float-right mt-2">{{ $project->likes->count() }}</span>
                                </small>
                            </a>
                        @endforeach
                    </div>

                    @if($paginate != "all")
                        {{ $projects->links() }}
                    @endif

                </div>
            </div>
        </div>
        @if (Auth::user()->staff != 1)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Selected Projects</div>

                <div class="card-body">

                    <div id="project-list" class="list-group mt-3">
                        @foreach($likedProjects as $project)
                            <a href="#liked_project_modal_{{ $project->id }}" data-toggle="modal" data-id="{{ $project->id }}" class="ui-state-default list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                  <h6 class="mb-1">{{ $project->title }}</h6>
                                </div>
                                <small class="project-list-rank">Rank: {{ $loop->iteration }}</small>
                            </a>
                        @endforeach                       
                    </div>

                    <button type="button" class="btn btn-outline-secondary mt-3" id="reorder-projects-button">Reorder Projects</button>

                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@foreach($projects as $project)
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="project_modal_{{ $project->id }}" role="dialog" tabindex="-1" aria-labelledby="project_modal_{{ $project->id }}label" aria-hidden="true">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">{{ $project->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <b>Supervisor: {{ $project->user()->first()->name }}</b>

                <br><br>

                {!! nl2br(e($project->description)) !!}

                <div class="row">

                    <div class="col">
                        <div class="form-check mt-3">
                            @if ($project->UG == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Suitable for UG
                            </label>
                        </div>
                        <div class="form-check mt-3">
                            @if ($project->MSc == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Suitable for MSc
                            </label>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-check mt-3">
                            @if ($project->experimental == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Experimental
                            </label>
                        </div>
                        <div class="form-check mt-3">
                            @if ($project->computational == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Computational
                            </label>
                        </div>
                        <div class="form-check mt-3">
                            @if ($project->hidden == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Hidden
                            </label>
                        </div>
                    </div>

                </div>

                <br>

                Number of students having selected this project: {{ $project->likes->count() }}

                <div class="row">
                    <div class="col">

                    @if (Auth::user()->staff != 1)
                        @if ($project->isLiked)
                            <!-- Button Trigger -->
                            <a href="{{ route('like-project', $project) }}"><button type="button" class="btn btn-outline-secondary mt-3">Deselect Project</button></a>

                            <!-- Button Trigger -->
                            <a href="{{ route('rankup-project', $project) }}"><button type="button" class="btn btn-outline-secondary mt-3">Rank-Up Project</button></a>

                            <!-- Button Trigger -->
                            <a href="{{ route('rankdown-project', $project) }}"><button type="button" class="btn btn-outline-secondary mt-3">Rank-Down Project</button></a>
                        @else
                            <!-- Button Trigger -->
                            <a href="{{ route('like-project', $project) }}"><button type="button" class="btn btn-outline-secondary mt-3" >Select Project</button></a>
                        @endif
                    @endif

                    @if (Auth::user()->admin == 1)
                    <!-- Button Trigger -->
                    <a href="/projects/{{ $project->id }}"><button type="button" class="btn btn-outline-secondary mt-3">View Project</button></a>
                    @endif

                    </div>
                </div>

            </div>

        </div>
      
    </div>
</div>
@endforeach

@foreach($likedProjects as $project)
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="liked_project_modal_{{ $project->id }}" role="dialog" tabindex="-1" aria-labelledby="liked_project_modal_{{ $project->id }}label" aria-hidden="true">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">{{ $project->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <b>Supervisor: {{ $project->user()->first()->name }}</b>

                <br><br>

                {!! nl2br(e($project->description)) !!}

                <div class="row">

                    <div class="col">
                        <div class="form-check mt-3">
                            @if ($project->UG == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Suitable for UG
                            </label>
                        </div>
                        <div class="form-check mt-3">
                            @if ($project->MSc == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Suitable for MSc
                            </label>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-check mt-3">
                            @if ($project->experimental == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Experimental
                            </label>
                        </div>
                        <div class="form-check mt-3">
                            @if ($project->computational == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Computational
                            </label>
                        </div>
                        <div class="form-check mt-3">
                            @if ($project->hidden == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Hidden
                            </label>
                        </div>
                    </div>

                </div>

                <br>

                Number of students having selected this project: {{ $project->likes->count() }}

                <div class="row">
                    <div class="col">

                    @if (Auth::user()->staff != 1)
                        @if ($project->isLiked)
                            <!-- Button Trigger -->
                            <a href="{{ route('like-project', $project) }}"><button type="button" class="btn btn-outline-secondary mt-3">Deselect Project</button></a>

                            <!-- Button Trigger -->
                            <a href="{{ route('rankup-project', $project) }}"><button type="button" class="btn btn-outline-secondary mt-3">Rank-Up Project</button></a>

                            <!-- Button Trigger -->
                            <a href="{{ route('rankdown-project', $project) }}"><button type="button" class="btn btn-outline-secondary mt-3">Rank-Down Project</button></a>
                        @else
                            <!-- Button Trigger -->
                            <a href="{{ route('like-project', $project) }}"><button type="button" class="btn btn-outline-secondary mt-3" >Select Project</button></a>
                        @endif
                    @endif

                    @if (Auth::user()->admin == 1)
                    <!-- Button Trigger -->
                    <a href="/projects/{{ $project->id }}"><button type="button" class="btn btn-outline-secondary mt-3">View Project</button></a>
                    @endif
                    
                    </div>
                </div>

            </div>

        </div>
      
    </div>
</div>
@endforeach

@endsection
