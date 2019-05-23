@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <span style="vertical-align: middle; height: 100%;">
                    Dashboard
                  </span>

                  @if (Auth::user()->staff == 1)
                  <!-- Button Trigger -->
                  <button type="button" class="btn btn-outline-secondary ml-1 float-right" data-toggle="modal" data-target="#addProjectModal">Add Project</button>

                  <!-- Button Trigger -->
                  <button type="button" class="btn btn-outline-secondary float-right" data-toggle="modal" data-target="#deleteProjectModal">Delete Project</button>
                  @endif

                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (Auth::user()->staff == 1)
                    Below are the projects you have created:

<!--                     <div class="list-group mt-3">
                        @foreach($userProjects as $project)
                            <a href="{{ route('project', $project->id) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                  <h6 class="mb-1">{{ $project->title }}</h6>
                                    <small class="ml-5">{{ \Carbon\Carbon::parse($project->updated_at)->format('d/m/Y') }}</small>
                                </div>
                              </a>
                        @endforeach                       
                    </div> -->

                    <table class="table table-hover mt-4">
                    <thead>
                      <tr>
                        <th scope="col" style="width: 40%">Project Name</th>
                        <th scope="col" style="width: 15%">1st Choice Students</th>
                        <th scope="col" style="width: 15%">2nd Choice Students</th>
                        <th scope="col" style="width: 15%">3rd Choice Students</th>
                        <th scope="col" style="width: 15%">Last Updated At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($userProjects as $project)
                      <tr class="link-table-row" data-href="{{ route('project', $project->id) }}">
                        <td>{{ $project->title }}</td>
                        <td>
                          @foreach($project->likes as $like)
                            @if($like->order_column == 1)
                              {{ $like->user->name }} @if( $like->user->getIsSelectedAttribute() && ($like->user->id == $project->selected_user_id || $like->user->id == $project->selected_user2_id)) ★ @endif <br>
                            @endif
                          @endforeach
                        </td>
                        <td>
                          @foreach($project->likes as $like)
                            @if($like->order_column == 2)
                              {{ $like->user->name }} @if( $like->user->getIsSelectedAttribute() && ($like->user->id == $project->selected_user_id || $like->user->id == $project->selected_user2_id)) ★ @endif <br>
                            @endif
                          @endforeach
                        </td>
                        <td>
                          @foreach($project->likes as $like)
                            @if($like->order_column == 3)
                              {{ $like->user->name }} @if($like->user->getIsSelectedAttribute() && ($like->user->id == $project->selected_user_id || $like->user->id == $project->selected_user2_id)) ★ @endif <br>
                            @endif
                          @endforeach
                        </td>
                        <td>{{ \Carbon\Carbon::parse($project->updated_at)->format('d/m/Y') }}</td>
                      </tr>
                      @endforeach
                    </tbody>

                    <div class="float-right">★ Selected Students</div>

                  </table>

                    @else

                    Below are the projects you have selected:

                    <div class="list-group mt-3">
                        @foreach($likedProjects as $project)
                            <a href="{{ route('project', $project->id) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                  <h6 class="mb-1">{{ $project->title }}</h6>
                                    <small class="ml-5">{{ \Carbon\Carbon::parse($project->updated_at)->format('d/m/Y') }}</small>
                                </div>
                                <small>by {{ $project->user()->first()->name }}</small> <br>
                                <br>
                                <small>{!! nl2br(e($project->description)) !!}
                                    <span class="badge badge-primary badge-pill float-right mt-2">{{ $project->likes->count() }}</span>
                                </small>
                              </a>
                        @endforeach                       
                    </div>

                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

@if (Auth::user()->staff == 1)
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProjectModalLabel">Add Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addProjectForm" name="addProjectForm" method="POST" onsubmit="" onreset="" action="{{ route('new-project') }}">
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="titleInput" class="col-sm-2 col-form-label">Title</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" placeholder="Please enter title of project" name="title">
                </div>
            </div>
            <div class="form-group row">
                <label for="descriptionTextArea" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" placeholder="Please enter description of project" name="description" onkeyup="textAreaAdjust(this)"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="true" name="UG">
                    <label class="form-check-label">
                      Suitable for UG
                    </label>
                  </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="true" name="MSc">
                    <label class="form-check-label">
                      Suitable for MSc
                    </label>
                  </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="true" name="experimental">
                    <label class="form-check-label">
                      Experimental
                    </label>
                  </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="true" name="computational">
                    <label class="form-check-label">
                      Computational
                    </label>
                  </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="true" name="hidden">
                    <label class="form-check-label">
                      Hidden
                    </label>
                  </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary float-right">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteProjectModalLabel">Delete Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['route' => 'delete-project', 'method' => 'DELETE']) !!}
                <div class="appear double midContainerContent">
                    <div class="form-group">
                        {!! Form::label('Delete Project') !!}
                        {!! Form::select('id', $selectUserProjects, old('id'), ['class'=>'form-control', 'placeholder'=>'Select Project']) !!}
                    </div>
                </div>
                <div class="editContent">
                    <div class="editContentButton">
                        <button class="btn btn-primary float-right" onclick="this.disabled=true;this.value='Submitting...'; this.form.submit();">Delete Project</button>
                    </div>
                </div>
        {!! Form::close()  !!}
      </div>
    </div>
  </div>
</div>
@endif

@endsection
