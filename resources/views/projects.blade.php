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
                            </div>
                        </div>
                    </form>

                    <div class="d-flex flex-row-reverse">
                        <button id="target" type="button" class="btn btn-light">
                          <i class="fa fa-sort" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div id="allprojects" class="list-group my-3">
                        @foreach($projects as $project)
                            <a href="{{ route('project', $project->id) }}" class="list-group-item list-group-item-action flex-column align-items-start">
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
                            <a href="/projects/{{ $project->id }}" data-id="{{ $project->id }}" class="ui-state-default list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                  <h6 class="mb-1">{{ $project->title }}</h6>
                                </div>
                            </a>
                        @endforeach                       
                    </div>

                    <button type="button" class="btn btn-outline-secondary mt-3" id="reorder-projects-button">Reorder Projects</button>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
