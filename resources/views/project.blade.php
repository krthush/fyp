@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5>{{ $project->title }}</h5></div>

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
                            <a href="{{ route('like-project', $project) }}"><button type="button" class="btn btn-outline-secondary mt-3">Select Project</button></a>
                        @endif

                        @if (Auth::user()->id === $project->user_id)
                            <!-- Button Trigger -->
                            <button type="button" class="btn btn-outline-secondary mt-3" data-toggle="modal" data-target="#editProjectModal">Edit Project</button>

                            <!-- Modal -->
                            <div class="modal fade bd-example-modal-lg" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="editProjectModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form id="editProjectForm" name="editProjectForm" method="POST" onsubmit="" onreset="" action="{{ route('update-project',$project->id) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PATCH') }}
                                        <div class="form-group row">
                                            <label for="titleInput" class="col-sm-2 col-form-label">Title</label>
                                            <div class="col-sm-10">
                                                <input class="form-control" type="text" value="{{ $project->title }}" name="title">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descriptionTextArea" class="col-sm-2 col-form-label">Description</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" name="description">{{ $project->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                              <div class="form-check">
                                                @if ($project->UG == true)
                                                <input class="form-check-input" type="checkbox" value="true" name="UG" checked>
                                                @else
                                                <input class="form-check-input" type="checkbox" value="true" name="UG">                                
                                                @endif
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
                                                @if ($project->MSc == true)
                                                <input class="form-check-input" type="checkbox" value="true" name="MSc" checked>
                                                @else
                                                <input class="form-check-input" type="checkbox" value="true" name="MSc">                                
                                                @endif
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
                                                @if ($project->ME4 == true)
                                                <input class="form-check-input" type="checkbox" value="true" name="ME4" checked>
                                                @else
                                                <input class="form-check-input" type="checkbox" value="true" name="ME4">                                
                                                @endif
                                                <label class="form-check-label">
                                                    Suitable for ME4
                                                </label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-10">
                                              <div class="form-check">
                                                @if ($project->experimental == true)
                                                <input class="form-check-input" type="checkbox" value="true" name="experimental" checked>
                                                @else
                                                <input class="form-check-input" type="checkbox" value="true" name="experimental">                                
                                                @endif
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
                                                @if ($project->computational == true)
                                                <input class="form-check-input" type="checkbox" value="true" name="computational" checked>
                                                @else
                                                <input class="form-check-input" type="checkbox" value="true" name="computational">                                
                                                @endif
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
                                                @if ($project->hidden == true)
                                                <input class="form-check-input" type="checkbox" value="true" name="hidden" checked>
                                                @else
                                                <input class="form-check-input" type="checkbox" value="true" name="hidden">                                
                                                @endif
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

                        @endif
                        
                        </div>
                    </div>

                    <br>

                    Number of students having selected this project: {{ $project->likes->count() }}

                    @if (Auth::user()->staff == 1)

                    <br>

                    Students who have selected this project and their rank:

                    <!-- Code to show which students have chosen project and their rank... -->
                    <div class="list-group mt-3">
                        @foreach($usersLiked as $user)
                            <div class="ui-state-default list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="align-middle">{{ $user->name }}</h6>
                                    @foreach($likeables as $like)
                                        @if ($like->user_id == $user->id)
                                        <h6 class="align-middle">{{ $like->order_column }}</h6>
                                        @endif
                                    @endforeach
                                    <!-- Button Trigger -->
                                    <a href="/projects/match/{{ $project->id }}/{{ $user->id }}"><button type="button" class="btn btn-outline-secondary" >Select User</button></a>
                                </div>
                            </div>
                        @endforeach                       
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
