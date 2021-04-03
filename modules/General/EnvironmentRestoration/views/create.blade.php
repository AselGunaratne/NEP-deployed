@extends('general')

@section('general')

<div class="container">
    <form action="/env-restoration/store" id="envForm" method="post">
        @csrf
        <!-- One "tab" for each step in the form: -->
        <div class="tab">
            <div class="container">
                <div class="row p-4 bg-white">
                    <div class="col border border-muted rounded-lg mr-2 p-4">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" placeholder="Enter Title" id="title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="title">Restored Land Parcel Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Land Parcel Name" name="landparceltitle">
                        </div>

                        <div class="form-group">
                            <label for="title">Environment Restoration Activity:</label>
                            <select name="environment_restoration_activity" class="custom-select">
                                <option selected>Select Activity</option>
                                @foreach($restoration_activities as $restoration_activity)
                                    <option value="{{$restoration_activity->id}}">{{$restoration_activity->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Ecosystem:</label>
                            <select name="ecosystem" class="custom-select">
                                <option selected>Select Ecosystem</option>
                                @foreach($ecosystems as $ecosystem)
                                    <option value="{{$ecosystem->id}}">{{$ecosystem->ecosystem_type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <a class="collapsed card-link text-dark" data-toggle="collapse" href="#collapseOne">Governing Organization for selected Land Parcel </a>
                            </div>
                            <div id="collapseOne" class="collapse">
                                <div class="card-body">
                                    @foreach($organizations as $organization)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="govOrg[]" value="{{$organization->id}}">{{$organization->title}}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="request_org">Organization to submit request to :</label>
                            <input type="text" class="form-control" placeholder="Enter Organization" id="request_org" name="request_org">
                        </div>
                    </div>
                    <div class="col border border-muted rounded-lg p-4">
                        <!-- ////////MAP GOES HERE -->
                        <div id="mapid" style="height:400px;" name="map"></div>

                        @error('polygon')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input id="polygon" type="hidden" name="polygon" class="form-control @error('polygon') is-invalid @enderror" value="{{request('polygon')}}" />                        <br>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck" value="1" name="isProtected">
                            <label class="custom-control-label" for="customCheck"><strong>Check if land is a protected area</strong></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab">
            <div class="container">
                <div class="row border rounded-lg p-4 bg-white">
                    <!-- creating the species table followed by ajax script to add and remove species in the table -->
                    <div class="table-responsive">
                        <h4> Species </h4>
                        <br/>
                        <form method="post" id="dynamic_form">
                            <span id="result"></span>
                            <table class="table table-bordered table-striped" id="species">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Height</th>
                                        <th>Dimensions</th>
                                        <th>Remarks</th>
                                        <th></th>
                                        <input type="hidden" class="form-control" name="status_species" value="1">

                                        <script>
                                            $(document).ready(function() {
                                                var count = 1;
                                                dynamic_species(count);

                                                function dynamic_species(number) {
                                                    html = '<tr>';
                                                    html += '<input type="hidden" name="environment_restoration_id[]" class="form-control" value="" />';
                                                    html += '<input type="hidden" name="statusSpecies[]" class="form-control" value="1" />';
                                                    html += '<td><select name="species_id[]" class="custom-select"><option selected>Select Species</option><option value="1">treespeciesname1</option><option value="2">treespeciesname2</option><option value="3">treespeciesname3 </option><option value="4">treespeciesname4</option></select></td>';
                                                    html += '<td><input type="text" name="quantity[]" class="form-control" /></td>';
                                                    html += '<td><input type="text" name="height[]" class="form-control" /></td>';
                                                    html += '<td><input type="text" name="dimension[]" class="form-control" /></td>';
                                                    html += '<td><input type="text" name="remark[]" class="form-control" /></td>';

                                                    if (number > 1) {
                                                        html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove Species</button></td></tr>';
                                                        $('tbody').append(html);
                                                    } else {
                                                        html += '<td><button type="button" name="add" id="add" class="btn btn-success">Add Species</button></td></tr>';
                                                        $('tbody').html(html);
                                                    }
                                                }
                                                $(document).on('click', '#add', function() {
                                                    count++;
                                                    dynamic_species(count);
                                                });

                                                $(document).on('click', '.remove', function() {
                                                    count--;
                                                    $(this).closest("tr").remove();
                                                });

                                                $('#dynamic_species').on('submit', function(event) {
                                                    event.preventDefault();
                                                    $.ajax({
                                                        url: '{{ route("store.dynamic-species") }}',
                                                        method: 'post',
                                                        data: $(this).serialize(),
                                                        dataType: 'json',
                                                        beforeSend: function() {
                                                            $('#save').attr('disabled', 'disabled');
                                                        },
                                                        success: function(data) {
                                                            if (data.error) {
                                                                var error_html = '';
                                                                for (var count = 0; count < data.error.length; count++) {
                                                                    error_html += '<p>' + data.error[count] + '</p>';
                                                                }
                                                                $('#result').html('<div class="alert alert-danger">' + error_html + '</div>');
                                                            } else {
                                                                dynamic_field(1);
                                                                $('#result').html('<div class="alert alert-success">' + data.success + '</div>');
                                                            }
                                                            $('#save').attr('disabled', false);
                                                        }
                                                    })
                                                });
                                            });
                                        </script>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <input type="hidden" class="form-control" name="status" value="1">
                            <input type="hidden" class="form-control" name="organization" value="{{Auth::user()->organization_id}}">
                            <input type="hidden" class="form-control" name="created_by" value="{{Auth::user()->id}}">
                            <input type="hidden" class="form-control" name="logs" value="0">
                        </form>
                    </div>
                </div>
            </div>
        </div>
            <br>
            <div style="overflow:auto;">
                <div style="float:right;">
                    <button type="button" id="prevBtn" class="btn bd-navbar text-white" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" id="nextBtn" class="btn btn-primary" onclick="nextPrev(1)">Next</button>
                </div>
            </div>
            <!-- Circles which indicates the steps of the form: -->
            <div style="text-align:center;margin-top:40px;">
                <span class="step"></span>
                <span class="step"></span>
            </div>
    </form>
</div>

<script type="text/javascript">
  ///STEPPER
  var currentTab = 0; // Current tab is set to be the first tab (0)
  showTab(currentTab); // Display the current tab

  function showTab(n) {
    // This function will display the specified tab of the form...
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    //... and fix the Previous/Next buttons:
    if (n == 0) {
      document.getElementById("prevBtn").style.display = "none";
    } else {
      document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
      document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
      document.getElementById("nextBtn").innerHTML = "Next";
    }
    //... and run a function that will display the correct step indicator:
    fixStepIndicator(n)
  }

  function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("tab");
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;
    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the form...
    if (currentTab >= x.length) {
      // ... the form gets submitted:
      document.getElementById("envForm").submit();
      return false;
    }
    // Otherwise, display the correct tab:
    showTab(currentTab);
  }

  function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByClassName("verifythis");
    // A loop that checks every input field in the current tab:
    for (i = 0; i < y.length; i++) {
      // If a field is empty...
      if (y[i].value == "") {
        // add an "invalid" class to the field:
        y[i].className += " invalid";
        // and set the current valid status to false
        valid = false;
      }
    }
    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
      document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid; // return the valid status
  }

  function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
      x[i].className = x[i].className.replace(" active", "");
    }
    //... and adds the "active" class on the current step:
    x[n].className += " active";
  }
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
        $('#polygon').val(JSON.stringify(layer.toGeoJSON()));
    });
</script>

@endsection