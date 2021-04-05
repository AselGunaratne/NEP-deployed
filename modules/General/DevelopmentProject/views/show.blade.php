@extends('home')

@section('cont')

<kbd><a href="/approval-item/showRequests" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<hr>
<div class="container">
    <dl class="row">
        <dt class="col-sm-3">Title:</dt>
        <dd class="col-sm-9">{{$development_project->title}}</dd>

        <dt class="col-sm-3">Category:</dt>
        <dd class="col-sm-9">Development Project</dd>

        @if($development_project->gazette_id)
        <dt class="col-sm-3">Gazette:</dt>
        <dd class="col-sm-9">{{$development_project->gazette->title}}</dd>
        @endif


        <dt class="col-sm-3">Description:</dt>
        <dd class="col-sm-9">
            <p>{{$development_project->description}}</p>
        </dd>

        <dt class="col-sm-3">Activity Organization:</dt>
        <dd class="col-sm-9">
            <p>{{$development_project->organization->title}}</p>
        </dd>

        <dt class="col-sm-3">Land Parcel:</dt>
        <dd class="col-sm-9">{{$development_project->land_parcel->title}}</dd>

        @if($development_project->land_size != 0)
        <dt class="col-sm-3">Land Size:</dt>
        <dd class="col-sm-9">{{$development_project->land_size}} {{$tree->land_size_unit}}</dd>
        @endif

        <dt class="col-sm-3">Status:</dt>
        <dd class="col-sm-9">{{$development_project->status->type}}</dd>

        <dt class="col-sm-3">Created at:</dt>
        <dd class="col-sm-9">{{$development_project->created_at}}</dd>
    </dl>
    <div class="border border-dark border-rounded">
        <div id="mapid" style="height:400px;" name="map"></div>
    </div>
    <div class="row">
        @isset($Photos)
            @if (count($Photos) > 0)
                @foreach($Photos as $photo)
                    <div class="col border border-muted rounded-lg mr-2 p-4">
                        <img class="img-responsive" src="{{asset('/storage/'.$photo)}}" alt="photo">
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
    console.log(polygon);

    //ADDING THE JSOON COORDINATES TO MAP
    var layer = L.geoJSON(JSON.parse(polygon)).addTo(map);

    // Adjust map to show the kml
    var bounds = layer.getBounds();
    map.fitBounds(bounds);
</script>
@endsection