@extends('general')

@section('general')
<hr>
<span>
    <h3 class="text-center bg-success text-light">{{session('message')}}</h3>
    <h3 class="text-center bg-warning text-light">{{session('warning')}}</h3>
</span>
<div class="container">
    <div class="container bg-white">
        <div class="row p-4 bg-white">
            @if($process_item->prerequisite == 0)
                <h3>Investigation of {{$process_item->form_type->type}} application no {{$process_item->form_id}} logged on {{date('d-m-Y',strtotime($item->created_at))}}</h3>
            @elseif($process_item->prerequisite == 1)
                <h3>Additional Investigation for {{$process_item->form_type->type}} application no {{$process_item->form_id}} logged on {{date('d-m-Y',strtotime($item->created_at))}}</h3>
            @endif
        </div>   
        <div class="row p-4 bg-white">
            <div class="col border border-muted rounded-lg mr-2 p-4">
                @switch($process_item->form_type_id)
                    @case('1')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Province</th>
                                    <th>District</th>
                                    <th>GS Division</th>
                                    <th>Special approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if($item->District == NULL)
                                    <td>Unassigned</td>
                                    @else
                                    <td>{{$item->District->province->province}}</td>
                                    @endif
                                    @if($item->district == NULL)
                                    <td>Unassigned</td>
                                    @else
                                    <td>{{$item->district->district}}</td>
                                    @endif 
                                    @if($item->gs_division == NULL)
                                    <td>Unassigned</td>
                                    @else
                                    <td>{{$item->gs_division->gs_division}}</td>
                                    @endif
                                    @if($item->special_approval==0)
                                        <td>Not a protected area</td>
                                    @elseif($item->special_approval==1)
                                        <td>Protected area</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Land size</th>
                                    <th>unit</th>
                                    <th>No of Trees</th>
                                    <th>No of Tree Species</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->land_size}}</td>
                                    <td>Acres</td>
                                    <td>{{$item->no_of_trees}}</td>
                                    <td>{{$item->no_of_tree_species}}<td>
                                </tr>
                            </tbody>
                        </table>
                        <h6>Additional Data</h6>
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                                <thead>
                                    <tr>
                                        <th>Number of Mamal Species</th>
                                        <th>Number of Amphibian Species</th>
                                        <th>Number of Reptile Species</th>
                                        <th>Number of Avian Species</th>
                                        <th>Number of Flora Species</th>
                                        <th>Tree Species special notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$item->no_of_mammal_species}}</td>
                                        <td>{{$item->no_of_amphibian_species}}</td>
                                        <td>{{$item->no_of_reptile_species}}</td>
                                        <td>{{$item->no_of_avian_species}}</td>
                                        <td>{{$item->no_of_flora_species}}</td>
                                        <td>{{$item->species_special_notes}}</td>
                                    </tr>
                                </tbody>
                        </table>
                    @break
                    @case('2')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Gazzete</th>
                                    <th>Protected Area</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <td>{{$item->title}}</td>
                                    @if($item->gazette==null)
                                        <td>No Gazzete</td>
                                    @else
                                    <td>{{$item->gazette->title}}</td>
                                    @endif
                                    @if($item->protected_area==0)
                                        <td>Not a protected area</td>
                                    @else
                                        <td>Protected area</td>
                                    @endif              
                                </tr>
                            </tbody>
                        </table>
                    @break
                    @case('3')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Activity</th>
                                    <th>Eco System</th>
                                    <th>Eco System Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->environment_restoration_activity->title}}</td>
                                    <td>{{$item->eco_system->ecosystem_type}}</td>
                                    <td>{{$item->eco_system->description}}</td>
                                </tr>
                            </tbody>
                        </table>
                    @break
                    @case('4')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Crime type</th>
                                    <th>description</th>
                                    <th>Date complained logged</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->crime_type->type}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                                </tr>
                            </tbody>
                        </table>
                    @break
                    @case('5')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>District</th>
                                    <th>Grama Niladari Division</th>
                                    <th>Protected Area</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if($item->district==null)
                                        <td>Not assigned</td>
                                    @else
                                        <td>{{$item->district->district}}</td>
                                    @endif
                                    @if($item->gs_division==null)
                                        <td>Not assigned</td>
                                    @else
                                    <td>{{$item->gs_division->gs_division}}</td>
                                    @endif
                                    @if($item->special_approval==0)
                                        <td>Not a protected area</td>
                                    @elseif($item->special_approval==1)
                                        <td>Protected area</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    @break
                @endswitch
            </div>
            <div class="col border border-muted rounded-lg mr-2 p-4">
                <div id="mapid" style="height:400px;" name="map"></div>
                @if($process_item->form_type_id!=5)
                    <button type="submit" class="btn btn-primary" ><a href="/approval-item/investigate/{{$land_process->id}}" class="text-dark">View More details</a></button>
                @endif
            </div>
        </div>
        @if($process_item->form_type_id == 1)
            <div class="row p-4 bg-white">
                <h6>Tree Data</h6>
                @if(count($tree_data) < 1)
                    <h1>No data</h1>
                @else
                    <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                        <thead>
                            <tr>
                                <th>Tree Species ID</th>
                                <th>Tree ID</th>
                                <th>Width at Breast Height</th>
                                <th>Height</th>
                                <th>Timber Volume</th>
                                <th>Timber Cubic</th>
                                <th>Age</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($x = 0; $x < count($tree_data); $x++)
                            <tr>
                                <td>{{$tree_data[$x]['tree_species_id']}}</td>
                                <td>{{$tree_data[$x]['tree_id']}}</td>
                                <td>{{$tree_data[$x]['width_at_breast_height']}}</td>
                                <td>{{$tree_data[$x]['height']}}</td>
                                <td>{{$tree_data[$x]['timber_volume']}}</td>
                                <td>{{$tree_data[$x]['timber_cubic']}}</td>
                                <td>{{$tree_data[$x]['age']}}</td>
                                <td>{{$tree_data[$x]['remark']}}</td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                @endif             
            </div>
        @endif
        <div class="row p-4 bg-white">
            <div class="col border border-muted rounded-lg mr-2 p-4">
                <h6>Progress</h6>
                @if (count($Process_item_progresses) > 0)
                    <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                        <thead>
                            <tr>
                                <th>Authority responsible</th>
                                <th>Remark</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Process_item_progresses as $Process_item_progress)<tr>
                                    <td>{{$Process_item_progress->User->name}}</td>
                                    <td>{{$Process_item_progress->remark}}</td>
                                    <td>{{$Process_item_progress->Status->status_title}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                @if(count($Process_item_progresses) < 1)
                <p>No progress yet</p>
                @endif
            </div>
            <div class="col border border-muted rounded-lg mr-2 p-4">
                <h6>Prerequisites</h6>
                @if (count($Prerequisites) > 0)
                    <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                        <thead>
                            <tr>
                                <th>Requested by</th>
                                <th>Assigned Organization</th>
                                <th>remarks</th>
                                <th>status</th>
                                <th>Cancel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Prerequisites as $prerequisite)<tr>
                                    <td>{{$prerequisite->created_by_user->name}}</td>
                                    <td>{{$prerequisite->Activity_organization->title}}</td>
                                    <td>{{$prerequisite->remark}}</td>
                                    <td>{{$prerequisite->status->type}}</td>
                                    <td><a href="/approval-item/cancelprerequisite/{{$prerequisite->id}}/{{ Auth::user()->id }}" class="text-muted">Cancel</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                @if (count($Prerequisites) < 1)
                    <p>No prerequisites made yet</p>
                @endif
            </div>
        </div>
        @if($process_item->form_type_id ===1 ||$process_item->form_type_id ===2 || $process_item->form_type_id ===4)
            <div class="row p-4 bg-white">
                <h6>Related images</h6>
            </div>
            <div class="row p-4 bg-white">
                @isset($Photos)
                    @if (count($Photos) > 0)
                            @foreach($Photos as $photo)
                                <div class="col border border-muted rounded-lg mr-2 p-4">
                                    <img class="img-responsive" src="{{URL::asset('/storage/'.$photo)}}" alt="photo">
                                    <a class="nav-link text-dark font-italic p-2" href="/crime-report/downloadimage/{{$photo}}">Download Image</a>
                                </div>
                            @endforeach
                    @endif
                    @if (count($Photos) < 1)
                            <p>No photos included in the application</p>
                    @endif
                @endisset
                @empty($Photos)
                    <p>No photos included in the application</p>
                @endempty
            </div>
            <div class="row p-4 bg-white">
                <div class="col border border-muted rounded-lg mr-2 p-4">
                    <h6>Tree removals in the same land Parcel</h6>
                    @if(count($Related_Treecuts) > 0)
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Land Size</th>
                                    <th>No of trees</th>
                                    <th>No of tree species</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Related_Treecuts as $related_treecut)
                                    <tr>
                                        <td>{{$related_treecut->land_size}}</td>
                                        <td>{{$related_treecut->no_of_trees}}</td>
                                        <td>{{$related_treecut->no_of_tree_species}}</td>
                                        <td>{{date('d-m-Y',strtotime($related_treecut->created_at))}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    @if(count($Related_Treecuts) < 1)
                        <p>No related tree removals in the same land parcel</p>
                    @endif
                </div>
                <div class="col border border-muted rounded-lg mr-2 p-4">
                    <h6>Development Projects in the same land Parcel</h6>
                    @if(count($Related_Devps) > 0)
                    <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Gazette Title</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Related_Devps as $related_devp)<tr>
                                <td>{{$related_devp->title}}</td>
                                    @if($related_devp->gazette==null)
                                        <td>No Gazzete</td>
                                    @else
                                    <td>{{$related_devp->gazette->title}}</td>
                                    @endif
                                
                                <td>{{date('d-m-Y',strtotime($related_devp->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    @if(count($Related_Devps) < 1)
                        <p>No related development projects in the same land parcel</p>
                    @endif
                </div>
            </div>
        @elseif($process_item->form_type_id ===3)
                <div class="row p-4 bg-white">
                    <div class="col border border-muted rounded-lg mr-2 p-4">
                        <h6>Species Data related to the restoration project</h6>
                        @if($tree_data == null)
                            <h1>No data</h1>
                        @else
                            <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                                <thead>
                                    <tr>
                                        <th>Species Type</th>
                                        <th>Species Name</th>
                                        <th>Species Scientific Name</th>
                                        <th>Number of species to be restored</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tree_data as $tree)
                                        <tr>
                                        <td>{{$tree->species->type}}</td>
                                        <td>{{$tree->species->title}}</td>
                                        <td>{{$tree->species->scientefic_name}}</td>
                                        <td>{{$tree->quantity}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>      
                </div>
        @else
            <div class="row p-4 bg-white">
            <h6>Governing Organizations related to the Land Parcel</h6>
            <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($LandOrganizations as $organization)
                                <tr>
                                    <td>{{$organization->organization->title}}</td>
                                    <td>{{$organization->organization->type->title}}</td>
                                    <td>{{$organization->organization->Description}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
            </div>
        @endif
        <div class="row p-4 bg-white">
            <div class="col border border-muted rounded-lg mr-2 p-4">
                <h6>Request additional investigation</h6>
                <form action="\approval-item\createprerequisite" method="post">
                    @csrf
                    <h6>Select Organization in charge</h6>
                    <div class="input-group mb-3">
                    <select name="organization" class="custom-select">
                        <option value="0" selected>Select Organization</option>
                    @foreach($Organizations as $organization)         
                        <option value="{{$organization->id}}">{{$organization->title}}</option>
                    @endforeach
                    </select>
                    @error('organization')
                        <div class="alert">                                   
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    </div>
                    <h6>Request</h6>
                    <div class="input-group mb-3">
                    </br>
                        <textarea  class="form-control" rows="3" name="request">
                        </textarea>
                        @error('request')
                            <div class="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                        <input type="hidden" class="form-control" name="create_by" value="{{ Auth::user()->id }}">
                        <input type="hidden" class="form-control" name="create_organization" value="{{ Auth::user()->organization_id }}">
                        <input type="hidden" class="form-control" name="process_id" value="{{ $process_item->id }}">
                    </div>
                    <div class="form-check">
                        <button type="submit" class="btn btn-primary" >Submit</button>
                    </div>
                </form>
            </div>
            <div class="col border border-muted rounded-lg mr-2 p-4">
                <h6>Change assigned staff member</h6>
                <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>name</th>
                            <th>email</th>
                            <th>assign</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td><a href="/approval-item/confirmassign/{{$user->id}}/{{$process_item->id}}" class="text-muted">assign</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row p-4 bg-white">
            <div class="col border border-muted rounded-lg mr-2 p-4">
                <h6>Save investigation Porgress<h6>
                <form action="\approval-item\progresssave\" method="post">
                            @csrf
                            <h6>Remark</h6>
                            <div class="input-group mb-3">
                            </br>
                                <textarea  class="form-control" rows="3" name="request">
                                </textarea>
                                @error('request')
                                    <div class="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                                <input type="hidden" class="form-control" name="create_by" value="{{ Auth::user()->id }}">
                                <input type="hidden" class="form-control" name="create_organization" value="{{ Auth::user()->organization_id }}">
                                <input type="hidden" class="form-control" name="process_id" value="{{ $process_item->id }}">
                            </div>
                            <h6>Status</h6>
                            <div class="input-group mb-3">
                                <select name="status" class="custom-select">
                                    <option value="0" selected>Select Status</option>
                                    @foreach($Process_item_statuses as $process_item_status)         
                                    <option value="{{$process_item_status->id}}">{{$process_item_status->status_title}}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                <div class="alert">                                   
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                            <div class="form-check">
                                <button type="submit" class="btn btn-primary" >Update</button>
                            </div>
                        </form>
                    </div>
                    <div class="col border border-muted rounded-lg mr-2 p-4">
                        <h6>Final approval/rejection of application<h6>
                        <form action="\approval-item\finalapproval\" method="post">
                    @csrf
                    <h6>Remark</h6>
                    <div class="input-group mb-3">
                    </br>
                        <textarea  class="form-control" rows="3" name="request">
                        </textarea>
                        @error('request')
                            <div class="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                        <input type="hidden" class="form-control" name="create_by" value="{{ Auth::user()->id }}">
                        <input type="hidden" class="form-control" name="create_organization" value="{{ Auth::user()->organization_id }}">
                        <input type="hidden" class="form-control" name="process_id" value="{{ $process_item->id }}">
                    </div>
                    <h6>Status</h6>
                    <div class="input-group mb-3">
                        <select name="status" class="custom-select">
                            <option value="0" selected>Select Status</option>         
                            <option value="4">Not Approved</option>
                            <option value="5">Approved</option>
                        </select>
                        @error('status')
                        <div class="alert">                                   
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="form-check">
                        <button type="submit" class="btn btn-primary" >Update</button>
                    </div>
                </form>
            </div>
        </div>
        @if($process_item->form_type_id ==5)
            <div class="container">
                <div class="row p-4 bg-white">
                    <button type="submit" class="btn btn-primary" ><a href="/approval-item/investigate/{{$process_item->prerequisite_id}}" class="text-dark">Back to {{$process_item->prerequisite_process->form_type->type}}</a></button>
                </div>
            </div>
        @endif
    </div>
</div>
<script type="text/javascript">
    var center = [7.2906, 80.6337];

    // Create the map
    var map = L.map('mapid').setView(center, 10);

    // Set up the OSM layer 
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Data © <a href="http://osm.org/copyright">OpenStreetMap</a>',
            maxZoom: 18
        }).addTo(map);

    //FROM LARAVEL THE COORDINATES ARE BEING TAKEN TO THE SCRIPT AND CONVERTED TO JSON
   var polygon = @json($polygon);
    var layer = L.geoJSON(JSON.parse(polygon)).addTo(map);
    

    // Adjust map to show the kml
    var bounds = layer.getBounds();
    map.fitBounds(bounds);
    
</script> 
@endsection