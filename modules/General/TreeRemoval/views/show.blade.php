@extends('home')

@section('cont')

<kbd><a href="/approval-item/showRequests" class="text-white font-weight-bolder"><i class="fas fa-chevron-left"></i></i> BACK</a></kbd>
<hr>
<div class="container">
    <div class="row">
        <div class="col border border-muted rounded-lg mr-2 p-2">
            <dl class="row">
                <dt class="col-sm-3">Province:</dt>
                <dd class="col-sm-9">{{$tree->district->province->province}}</dd>

                <dt class="col-sm-3">District:</dt>
                <dd class="col-sm-9">{{$tree->district->district}}</dd>

                <dt class="col-sm-3">Grama Sevaka Division:</dt>
                <dd class="col-sm-9">{{$tree->gs_division->gs_division}}</dd>

                <dt class="col-sm-3">Description:</dt>
                <dd class="col-sm-9">
                    <p>{{$tree->description}}</p>
                </dd>

                <dt class="col-sm-3">Activity Organization:</dt>
                <dd class="col-sm-9">
                    <p>{{$tree->organization->title}}</p>
                </dd>

                <dt class="col-sm-3">Category:</dt>
                <dd class="col-sm-9">Tree Removal</dd>

                @if($tree->land_size != 0)
                <dt class="col-sm-3">Land Size:</dt>
                <dd class="col-sm-9">{{$tree->land_size}} {{$tree->land_size_unit}}</dd>
                @endif

                <dt class="col-sm-3">Number of Trees:</dt>
                <dd class="col-sm-9">{{$tree->no_of_trees}}</dd>

                <dt class="col-sm-3">Number of Tree Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_tree_species}}</dd>

                <dt class="col-sm-3">Number of Mammal Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_mammal_species}}</dd>

                <dt class="col-sm-3">Number of Amphibian Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_amphibian_species}}</dd>

                <dt class="col-sm-3">Number of Reptile Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_reptile_species}}</dd>

                <dt class="col-sm-3">Number of Avian Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_avian_species}}</dd>

                <dt class="col-sm-3">Number of Floral Species:</dt>
                <dd class="col-sm-9">{{$tree->no_of_flora_species}}</dd>

                <dt class="col-sm-3">Species Special Notes:</dt>
                <dd class="col-sm-9">
                    <p>{{$tree->species_special_notes}}</p>
                </dd>

                <dt class="col-sm-3">Special Approval:</dt>
                <dd class="col-sm-9">{{$tree->special_approval}}</dd>

                <dt class="col-sm-3">Land Parcel:</dt>
                <dd class="col-sm-9">{{$tree->land_parcel->title}}</dd>

                <dt class="col-sm-3">Status:</dt>
                <dd class="col-sm-9">{{$tree->status->type}}</dd>

                <dt class="col-sm-3">Created at:</dt>
                <dd class="col-sm-9">{{$tree->created_at}}</dd>
        </div>
        <div class="col border border-muted rounded-lg mr-2 p-2">
            <dt class="col-sm-3">Properties</dt>
            <hr>
            @if($location==0)
            <dd class="col-sm-9">No Properties</dd>
            @else
            @for($x = 0; $x < count($location); $x++) <dd class="col-sm-9">
                <dl class="row">
                    <dt class="col-sm-7">Tree Species ID:</dt>
                    <dd class="col-sm-5">{{$location[$x]['tree_species_id']}}</dd>

                    <dt class="col-sm-7">Tree ID:</dt>
                    <dd class="col-sm-5">{{$location[$x]['tree_id']}}</dd>

                    <dt class="col-sm-7">Width at Breast Height:</dt>
                    <dd class="col-sm-5">{{$location[$x]['width_at_breast_height']}}</dd>

                    <dt class="col-sm-7">Height:</dt>
                    <dd class="col-sm-5">{{$location[$x]['height']}}</dd>

                    <dt class="col-sm-7">Timber Volume:</dt>
                    <dd class="col-sm-5">{{$location[$x]['timber_volume']}}</dd>

                    <dt class="col-sm-7">Cubic Feet:</dt>
                    <dd class="col-sm-5">{{$location[$x]['timber_cubic']}}</dd>

                    <dt class="col-sm-7">Age</dt>
                    <dd class="col-sm-5">{{$location[$x]['age']}}</dd>

                    <dt class="col-7">Remarks</dt>
                    <dd class="col-5">{{$location[$x]['remark']}}</dd>
                </dl>
                </dd>
                @endfor
                @endif
                </dl>
        </div>
    </div>



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

<script>
    /// BACK BUTTON
    function goBack() {
        window.history.back();
    }

    /// MAP MODULE
    var center = [7.2906, 80.6337];

    // Create the map
    var map = L.map('mapid').setView(center, 10);

    // Set up the OSM layer 
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Data ?? <a href="http://osm.org/copyright">OpenStreetMap</a>',
            maxZoom: 18
        }).addTo(map);


    var polygon = @json($polygon);
    var layer = L.geoJSON(JSON.parse(polygon)).addTo(map);

    // Adjust map to show the kml
    var bounds = layer.getBounds();
    map.fitBounds(bounds);
</script>
@endsection