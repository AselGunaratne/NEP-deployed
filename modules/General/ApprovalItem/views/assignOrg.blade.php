@extends('general')

@section('general')
<h3 class="p-3 display-4">Assigning Organizations</h3>
<hr>
<div class="container">
    <div class="container bg-white">
        <div class="row p-4 bg-white">
            <div class="col border border-muted rounded-lg mr-2 p-4">
                <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Date Application logged</th>
                            @if($process_item->activity_organization ==null)
                                <th>Organization Assigned (Non registered)</th>
                            @else
                                <th>Organization Assigned</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$process_item->form_type->type}}</td>
                            <td>{{date('d-m-Y',strtotime($process_item->created_at))}}</td>
                            @if($process_item->activity_organization ==null)
                                <td>{{$process_item->ext_requestor}}</td>
                            @else
                                <td>{{$process_item->Activity_organization->title}}</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                @switch($process_item->form_type_id)
                    @case('1')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>District</th>
                                    <th>Grama Niladari Division</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->district->district}}</td>
                                    <td>{{$item->gs_division_id}}</td>
                                    <td>{{$item->description}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Land Size</th>
                                    <th>Number of Trees</th>
                                    <th>Number of Tree Species</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->land_size}}</td>
                                    <td>{{$item->no_of_trees}}</td>
                                    <td>{{$item->no_of_tree_species}}<td>
                                </tr>
                            </tbody>
                        </table>
                    @break
                    @case('2')
                        <table class="table table-light table-striped border-secondary rounded-lg mr-4">
                            <thead>
                                <tr>
                                    <th>Project Title</th>
                                    <th>Gazette</th>
                                    <th>Grama Niladari Division</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->title}}</td>
                                @if($item->gazette==null)
                                    <td>No Gazette</td>
                                @else
                                    <td>{{$item->gazette->title}}</td>
                                @endif
                                    <td>{{$item->gs_division_id}}</td>
                                    <td>{{$item->description}}</td>
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
                                    <th>Crime Type</th>
                                    <th>Description</th>
                                    <th>Land Parcel</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$item->crime_type->type}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->land_parcel->title}}</td>
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
                                    @else
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
                <button type="submit" class="btn btn-primary" ><a href="/approval-item/assignorganization/{{$land_process->id}}" class="text-dark">View More details</a></button>
                @endif
            </div>
        </div>
        @if($process_item->form_type_id==1)
        <div class="row p-4 bg-white"> 
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
        </div>
        @endif
        @if($process_item->form_type_id==5)
            <div class="row p-4 bg-white">
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
        @isset($Photos)
        <div class="row p-4 bg-white">
            <div class="card-deck">
                @foreach($Photos as $photo)
                <div class="card" style="background-color:#99A3A4">
                    <img class="card-img-top" src="{{asset('/storage/'.$photo)}}" alt="photo">
                    <div class="card-body text-center">
                    <a class="nav-link text-dark font-italic p-2" href="/item-report/downloadimage/{{$photo}}">Download Image</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endisset
    </div>
    @if($process_item->form_type_id!=5)
        <div class="container">
            <div class="row p-4 bg-white">
                <h6>Change Assigned Organization</h6>
            </div>
        </div>
        <div class="container">
            <div class="row p-4 bg-white">
                <div class="col border border-muted rounded-lg mr-2 p-4">       
                    <p>System registered Organizations</p>
                    <table class="table  border-secondary rounded-lg mr-4">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Organizations as $organization)
                            <tr>
                                <td>{{$organization->title}}</td>
                                <td><a href="/approval-item/changeassignOrganization/{{$organization->id}}/{{$process_item->id}}" class="text-dark">assign</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col border border-muted rounded-lg mr-2 p-4">
                    <p>Non registered Organizations</p>
                    <form action="\approval-item\changeassignOrganization" method="post">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Organization name" name="organization" value="{{ old('organization') }}"/>
                            @error('organization')
                                <div class="alert">                                   
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror 
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Organization email" name="email" value="{{ old('email') }}"/>
                            @error('email')
                                <div class="alert">                                   
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror 
                            <input type="hidden" class="form-control" name="create_by" value="{{ Auth::user()->id }}">
                            <input type="hidden" class="form-control" name="create_organization" value="{{ Auth::user()->organization_id }}">
                            <input type="hidden" class="form-control" name="process_id" value="{{ $process_item->id }}">
                        </div>
                        <div class="form-check">
                            <button type="submit" class="btn btn-primary" >Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="container">
            <div class="row p-4 bg-white">
                <button type="submit" class="btn btn-primary" ><a href="/approval-item/assignorganization/{{$process_item->prerequisite_id}}" class="text-dark">Back to {{$process_item->prerequisite_process->form_type->type}}</a></button>
            </div>
        </div>
    @endif
   
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