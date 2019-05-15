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

                    <form id="searchProjectForm" name="searchProjectForm" method="POST" action="{{ route('search-projects') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="input-group input-group-md">
                                <input class="form-control" type="text" placeholder="What are you looking for?" name="search">
                                <div class="input-group-append">
                                    <select class="btn btn-outline-secondary rounded-0" name="order">
                                        <option value="relevance">Order by Relevance</option>
                                        <option value="name">Order by Name</option>
                                        <option value="author">Order by Author</option>
                                        <option value="date">Order by Date</option>
                                        <option value="popularity">Order by Popularity</option>
                                    </select>
                                    <button class="btn btn-outline-secondary" onclick="this.disabled=true;this.value='Submitting...'; this.form.submit();">Search</button>
                                </div>
                                <div class="d-flex flex-row-reverse">
                                    <button id="target" type="button" class="btn btn-light">
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

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Selected Projects</div>

                <div class="card-body">

                    <div id="project-list" class="list-group mt-3">
                        @foreach($likedProjects as $project)
<!--                             <a href="/projects/{{ $project->id }}" data-id="{{ $project->id }}" class="ui-state-default list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                  <h6 class="mb-1">{{ $project->title }}</h6>
                                </div>
                            </a> -->
                            <a href="#liked_project_modal_{{ $project->id }}" data-toggle="modal" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                  <h6 class="mb-1">{{ $project->title }}</h6>
                                </div>
                                <small>Rank: {{ $loop->iteration }}</small>
                            </a>
                        @endforeach                       
                    </div>

                    <button type="button" class="btn btn-outline-secondary mt-3" id="reorder-projects-button">Reorder Projects</button>

                </div>
            </div>
        </div>
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
                        <div class="form-check mt-3">
                            @if ($project->ME4 == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Suitable for ME4
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
                        <div class="form-check mt-3">
                            @if ($project->ME4 == true)
                            <input class="form-check-input" type="checkbox" disabled checked>
                            @else
                            <input class="form-check-input" type="checkbox" disabled>                                
                            @endif
                            <label class="form-check-label">
                                Suitable for ME4
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
                    
                    </div>
                </div>

            </div>

        </div>
      
    </div>
</div>
@endforeach

@endsection
