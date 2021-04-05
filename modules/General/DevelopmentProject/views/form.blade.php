@extends('general')

@section('general')

<div class="container">
    <form action="/dev-project/saveForm" method="post" id="devForm" enctype="multipart/form-data">
        @csrf

        <div class="container">
            <div class="row p-4 bg-white">
                <div class="col border border-muted rounded-lg mr-2 p-4">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" placeholder="Enter Title" id="title" name="title" value="{{ old('title') }}">
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        District:*<input type="text" class="form-control typeahead2 @error('district') is-invalid @enderror" value="{{ old('district') }}" placeholder="Search" name="district" />
                        @error('district')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        GS Division:*<input type="text" class="form-control typeahead4 @error('gs_division') is-invalid @enderror" value="{{ old('gs_division') }}" placeholder="Search" name="gs_division" />
                        @error('gs_division')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        Gazette for Development Project (Optional):<input type="text" class="form-control typeahead" placeholder="Search" name="gazette" value="{{ old('gazette') }}" />
                        @error('gazette')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        Activity Organization:*<input type="text" class="form-control typeahead3" placeholder="Search" name="organization" value="{{ old('organization') }}" />
                        @error('organization')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description:*</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" rows="2" id="description" name="description">{{{ old('description') }}}</textarea>
                        @error('description')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col border border-muted rounded-lg mr-2 p-4">
                    <div class="form-group" id="dynamicAddRemove2">
                        <label for="images">Image (Optional)</label>
                        <div class="custom-file mb-3">
                            <input type="file" id="images" name="images[0]">
                            <button type="button" name="add" id="add-btn2" class="btn btn-success">Add More</button>
                        </div>
                    </div>
                    <br>
                    <hr><br>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck2" value="1" name="checkExternalRequestor" {{ old('checkExternalRequestor') == "1" ? 'checked' : ''}}>
                            <label class="custom-control-label" for="customCheck2"><strong>Creating on behalf of non-registered user</strong></label>
                        </div>
                    </div>
                    <div class="form-group">
                        External Requestor:<input type="text" class="form-control @error('externalRequestor') is-invalid @enderror" value="{{ old('externalRequestor') }}" name="externalRequestor" placeholder="Enter NIC" />
                        @error('externalRequestor')
                        <div class="alert alert-danger">The NIC format is Invalid</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        External Requestor Email:<input type="text" class="form-control @error('erEmail') is-invalid @enderror" value="{{ old('erEmail') }}" placeholder="Enter email" name="erEmail" />
                        @error('erEmail')
                        <div class="alert alert-danger">Please Enter a Valid Email</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row p-4 bg-white">
                <div class="col border border-muted rounded-lg p-4">
                    <!-- MAP CONTENT -->
                    <h4>Land Parcel Details</h4>
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="title">Land Title:*</label>
                            <input type="text" class="form-control @error('landTitle') is-invalid @enderror" value="{{ old('landTitle') }}" placeholder="Enter Land Title" id="landTitle" name="landTitle">
                            @error('landTitle')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="land_extent">Land Extent (In Acres)</label>
                            <input type="text" class="form-control typeahead3" value="{{ old('land_extent') }}" id="land_extent" name="land_extent">
                            @error('land_extent')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="accordion" class="mb-3">
                            <div class="card mb-3">
                                <div class="card-header bg-white">
                                    <a class="collapsed card-link text-dark" data-toggle="collapse" href="#collapseone">
                                        Organizations Governing Land (Optional)
                                    </a>
                                </div>
                                <div id="collapseone" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <strong>Select 1 or More</strong>
                                        <fieldset>
                                            @foreach($organizations as $organization)
                                            <input type="checkbox" name="land_governing_orgs[]" value="{{$organization->id}}" @if( is_array(old('land_governing_orgs')) && in_array($organization->id, old('land_governing_orgs'))) checked @endif><label class="ml-2">{{$organization->title}}</label> <br>
                                            @endforeach
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-white">
                                    <a class="collapsed card-link text-dark" data-toggle="collapse" href="#collapsetwo">
                                        Gazettes Relavant to Land (Optional)
                                    </a>
                                </div>
                                <div id="collapsetwo" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <strong>Select 1 or More</strong>
                                        <fieldset>
                                            @foreach($gazettes as $gazette)
                                            <input type="checkbox" name="land_gazettes[]" value="{{$gazette->id}}" @if( is_array(old('land_gazettes')) && in_array($gazette->id, old('land_gazettes'))) checked @endif> <label class="ml-2">{{$gazette->title}}</label> <br>
                                            @endforeach
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Upload KML File</label>
                        <input type="file" name="select_file" id="select_file" />
                        <input type="button" name="upload" id="upload" class="btn btn-primary" value="Upload">
                    </div>
                    <br>
                    <!-- ////////MAP GOES HERE -->
                    <div id="mapid" style="height:400px;" name="map"></div>
                    @error('polygon')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <br>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck" value="1" name="isProtected" {{ old('isProtected') == "1" ? 'checked' : ''}}>
                        <label class="custom-control-label" for="customCheck"><strong>Is Land a Protected Area?</strong></label>
                    </div>

                    <!-- saving the coordinates of the kml file -->
                    <input id="polygon" type="text" name="polygon" class="form-control @error('polygon') is-invalid @enderror" value="{{request('polygon')}}" />

                    <!-- Saving the KML file in storage -->
                    <input id="kml" type="text" name="kml" class="form-control" value="{{request('kml')}}" />

                    <div>
                        <button type="submit" class="btn bd-navbar text-light">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" class="form-control" name="createdBy" value="{{Auth::user()->id}}">
    </form>
</div>


<script type="text/javascript">
    //photos add
    var i = 0;
    $("#add-btn2").click(function() {
        ++i;
        $("#dynamicAddRemove2").append(
            '<input type="file" id="images" name="images[' + i + ']">');
    });

    ///TYPEAHEAD
    var path = "{{route('gazette')}}";
    $('input.typeahead').typeahead({
        source: function(terms, process) {

            return $.get(path, {
                terms: terms
            }, function(data) {
                console.log(data);
                objects = [];
                data.map(i => {
                    objects.push(i.gazette_number)
                })
                console.log(objects);
                return process(objects);
            })
        },
    });

    //THIS USES THE AUTOMECOMPLETE FUNCTION IN TREE REMOVAL CONTROLLER
    var path3 = "{{route('organization')}}";
    $('input.typeahead3').typeahead({
        source: function(terms, process) {

            return $.get(path3, {
                terms: terms
            }, function(data) {
                console.log(data);
                objects = [];
                data.map(i => {
                    objects.push(i.title)
                })
                console.log(objects);
                return process(objects);
            })
        },
    });

    var path2 = "{{route('district')}}";
    $('input.typeahead2').typeahead({
        source: function(terms, process) {

            return $.get(path2, {
                terms: terms
            }, function(data) {
                console.log(data);
                objects = [];
                data.map(i => {
                    objects.push(i.district)
                })
                console.log(objects);
                return process(objects);
            })
        },
    });

    var path4 = "{{route('gramasevaka')}}";
    $('input.typeahead4').typeahead({
        source: function(terms, process) {

            return $.get(path4, {
                terms: terms
            }, function(data) {
                console.log(data);
                objects = [];
                data.map(i => {
                    objects.push(i.gs_division)
                })
                console.log(objects);
                return process(objects);
            })
        },
    });



    ///SCRIPT FOR THE MAP
    var center = [7.2906, 80.6337];

    // Create the map
    var map = L.map('mapid').setView(center, 10);

    // Set up the OSM layer 
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Data © <a href="http://osm.org/copyright">OpenStreetMap</a>',
            maxZoom: 18
        }).addTo(map);


    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    var drawControl = new L.Control.Draw({
        position: 'topright',
        draw: {
            polygon: {
                shapeOptions: {
                    color: 'purple'
                },
                allowIntersection: false,
                drawError: {
                    color: 'orange',
                    timeout: 1000
                },
                showArea: true,
                metric: false,
                repeatMode: true
            },
            polyline: {
                shapeOptions: {
                    color: 'red'
                },
            },
            circlemarker: false,
            rect: {
                shapeOptions: {
                    color: 'green'
                },
            },
            circle: false,
        },
        edit: {
            featureGroup: drawnItems
        }
    });
    map.addControl(drawControl);

    map.on('draw:created', function(e) {
        var type = e.layerType,
            layer = e.layer;

        if (type === 'marker') {
            layer.bindPopup('A popup!');
        }

        drawnItems.addLayer(layer);
        $('#polygon').val(JSON.stringify(drawnItems.toGeoJSON()));

        ///Converting your layer to a KML
        $('#kml').val(tokml(drawnItems.toGeoJSON()));
    });

    ///UPLOADING A FILE AND RETRIEVING AND CREATING A LAYER FROM IT.
    document.getElementById("upload").addEventListener("click", function() {
        var data = new FormData(document.getElementById("devForm"));
        event.preventDefault();
        $.ajax({
            url: "{{ route('ajaxmap.action') }}",
            method: "POST",
            data: data,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                $('#message').css('display', 'block');
                $('#message').html(data.message);
                $('#message').addClass(data.class_name);
                $('#uploaded_image').html(data.uploaded_image);
                var tmp = data.uploaded_image;
                $('#loc').val(JSON.stringify(tmp));
                console.log(tmp);
                fetch(`/${tmp}`)
                    .then(res => res.text())
                    .then(kmltext => {
                        // Create new kml overlay
                        const track = new omnivore.kml.parse(kmltext);
                        map.addLayer(track);

                        //SAVING THE UPLOADED COORDIATE LAYER TO GEOJSON
                        $('#polygon').val(JSON.stringify(track.toGeoJSON()));

                        // Adjust map to show the kml
                        const bounds = track.getBounds();
                        map.fitBounds(bounds);
                    }).catch((e) => {
                        console.log(e);
                    })
            }
        })

    });






    ///OLD SCRIPT FOR THE MAP
    // var center = [7.2906, 80.6337];

    // // Create the map
    // var map = L.map('mapid').setView(center, 10);

    // // Set up the OSM layer 
    // L.tileLayer(
    //     'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //         attribution: 'Data © <a href="http://osm.org/copyright">OpenStreetMap</a>',
    //         maxZoom: 18
    //     }).addTo(map);


    // var drawnItems = new L.FeatureGroup();
    // map.addLayer(drawnItems);

    // var drawControl = new L.Control.Draw({
    //     position: 'topright',
    //     draw: {
    //         polygon: {
    //             shapeOptions: {
    //                 color: 'purple'
    //             },
    //             allowIntersection: false,
    //             drawError: {
    //                 color: 'orange',
    //                 timeout: 1000
    //             },
    //             showArea: true,
    //             metric: false,
    //             repeatMode: true
    //         },
    //         polyline: {
    //             shapeOptions: {
    //                 color: 'red'
    //             },
    //         },
    //         circlemarker: false,
    //         rect: {
    //             shapeOptions: {
    //                 color: 'green'
    //             },
    //         },
    //         circle: false,
    //     },
    //     edit: {
    //         featureGroup: drawnItems
    //     }
    // });
    // map.addControl(drawControl);

    // map.on('draw:created', function(e) {
    //     var type = e.layerType,
    //         layer = e.layer;

    //     if (type === 'marker') {
    //         layer.bindPopup('A popup!');
    //     }

    //     drawnItems.addLayer(layer);
    //     $('#polygon').val(JSON.stringify(drawnItems.toGeoJSON()));

    //     ///Converting your layer to a KML
    //     // var json = drawnItems.toGeoJSON();
    //     // var kml = tokml(json);
    //     // console.log(kml);
    //     $('#kml').val(tokml(drawnItems.toGeoJSON()));


    // });
</script>
@endsection