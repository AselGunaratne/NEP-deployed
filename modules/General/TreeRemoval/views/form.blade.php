@extends('general')

@section('general')

<div class="container">

  <form action="/tree-removal/save" method="post" id="regForm" enctype="multipart/form-data">
    @csrf
    <!-- One "tab" for each step in the form: -->
    <div class="tab">
      <div class="container">
        <div class="row border rounded-lg p-4 bg-white">
          <div class="col border border-muted rounded-lg mr-2 p-2">
            <div class="row p-2">

              <div class="col p-2">
                <div class="form-group">
                  District:*<input type="text" class="form-control typeahead2 @error('district') is-invalid @enderror" value="{{ old('district') }}" placeholder="Search" name="district" />
                  @error('district')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col p-2">
                <div class="form-group">
                  GS Division:*<input type="text" class="form-control typeahead4 @error('gs_division') is-invalid @enderror" value="{{ old('gs_division') }}" placeholder="Search" name="gs_division" />
                  @error('gs_division')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              Activity Organization:*<input type="text" class="form-control typeahead3 @error('activity_organization') is-invalid @enderror" value="{{ old('activity_organization') }}" name="activity_organization" placeholder="Search Organizations" />
              @error('activity_organization')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>
            <br>
            <hr>
            <!-- MAP CONTENT -->
            <h4>Land Parcel Details</h4>
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
            <input id="polygon" type="hidden" name="polygon" class="form-control @error('polygon') is-invalid @enderror" value="{{request('polygon')}}" />

            <!-- Saving the KML file in storage -->
            <input id="kml" type="hidden" name="kml" class="form-control" value="{{request('kml')}}" />

          </div>
          <div class="col border border-muted rounded-lg">
            <div class="row p-2 mt-2">
              <div class="col">
                <div class="form-group">
                  <label for="number_of_trees">Number of Trees</label>
                  <input type="text" class="form-control @error('number_of_trees') is-invalid @enderror" value="{{ old('number_of_trees') }}" id="number_of_trees" name="number_of_trees">
                  @error('number_of_trees')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="number_of_tree_species">Number of Tree Species</label>
                  <input type="text" class="form-control" id="number_of_tree_species" name="number_of_tree_species">
                  @error('number_of_tree_species')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="number_of_flora_species">Number of Flora Species</label>
                  <input type="text" class="form-control" id="number_of_flora_species" name="number_of_flora_species">
                  @error('number_of_flora_species')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="number_of_reptile_species">Number of Reptile Species</label>
                  <input type="text" class="form-control" id="number_of_reptile_species" name="number_of_reptile_species">
                  @error('number_of_reptile_species')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label for="number_of_mammal_species">Number of Mammal Species</label>
                  <input type="text" class="form-control" id="number_of_mammal_species" name="number_of_mammal_species">
                  @error('number_of_mammal_species')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="number_of_amphibian_species">Number of Ambhibian Species</label>
                  <input type="text" class="form-control" id="number_of_amphibian_species" name="number_of_amphibian_species">
                  @error('number_of_amphibian_species')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="number_of_fish_species">Number of Fish Species</label>
                  <input type="text" class="form-control" id="number_of_fish_species" name="number_of_fish_species">
                  @error('number_of_fish_species')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="number_of_avian_species">Number of Avian Species</label>
                  <input type="text" class="form-control" id="number_of_avian_species" name="number_of_avian_species">
                  @error('number_of_avian_species')
                  <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>

            </div>
            <div class="form-group">
              <label for="species_special_notes">Species Special Notes</label>
              <textarea class="form-control" rows="1" id="species_special_notes" name="species_special_notes"></textarea>
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control @error('description') is-invalid @enderror" rows="2" id="description" name="description">{{{ old('description') }}}</textarea>
              @error('description')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>

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
      </div>
    </div>
    <div class="tab">
      <div class="container">
        <div class="row border rounded-lg p-4 bg-white">
          <table class="table" id="dynamicAddRemove">
            <tr>
              <th>Species</th>
              <th>Tree ID</th>
              <th>Width at Breast Height</th>
              <th>Height</th>
              <th>Timber Volume</th>
              <th>Cubic Feet</th>
              <th>Age</th>
            </tr>
            <tr>
              <td><input type="text" name="location[0][tree_species_id]" placeholder="Enter ID" class="form-control typeahead6" /></td>
              <td><input type="text" name="location[0][tree_id]" placeholder="Enter ID" class="form-control" /></td>
              <td><input type="text" name="location[0][width_at_breast_height]" placeholder="Enter Width" class="form-control" /></td>
              <td><input type="text" name="location[0][height]" placeholder="Enter Height" class="form-control" /></td>
              <td><input type="text" name="location[0][timber_volume]" placeholder="Enter Volume" class="form-control" /></td>
              <td><input type="text" name="location[0][timber_cubic]" placeholder="Enter Cubic" class="form-control" /></td>
              <td><input type="text" name="location[0][age]" placeholder="Enter Age" class="form-control" /></td>
              <td rowspan="2"><button type="button" name="add" id="add-btn" class="btn bd-navbar text-white">Add</button></td>
            </tr>
            <tr>
              <td colspan="7"><textarea name="location[0][remark]" placeholder="Enter Remarks" class="form-control" rows="3"></textarea></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <br>
    <div style="overflow:auto;">
      <div style="float:right;">
        <button type="button" id="prevBtn" class="btn bd-navbar text-white" onclick="nextPrev(-1)">Previous</button>
        <button type="button" id="nextBtn" class="btn bd-navbar text-white" onclick="nextPrev(1)">Next</button>
      </div>
    </div>
    <!-- Circles which indicates the steps of the form: -->
    <div style="text-align:center;margin-top:40px;">
      <span class="step"></span>
      <span class="step"></span>
    </div>
    <input type="hidden" class="form-control" name="createdBy" value="{{Auth::user()->id}}">
  </form>
</div>


<script>
  //photos add
  var i = 0;
  $("#add-btn2").click(function() {
    ++i;
    $("#dynamicAddRemove2").append(
      '<input type="file" id="images" name="images[' + i + ']">');
  });

  //STEPPER
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
      document.getElementById("regForm").submit();
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


  ///TYPEAHEAD
  // var path = "{{route('province')}}";
  // $('input.typeahead').typeahead({
  //   source: function(terms, process) {

  //     return $.get(path, {
  //       terms: terms
  //     }, function(data) {
  //       console.log(data);
  //       objects = [];
  //       data.map(i => {
  //         objects.push(i.province)
  //       })
  //       console.log(objects);
  //       return process(objects);
  //     })
  //   },
  // });

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

  var path6 = "{{route('species')}}";
  $('input.typeahead6').typeahead({
    source: function(terms, process) {

      return $.get(path6, {
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

  /// SCRIPT FOR THE DYNAMIC COMPONENT
  var i = 0;
  $("#add-btn").click(function() {
    ++i;
    $("#dynamicAddRemove").append(
      '<tr><td><input type="text" name="location[' + i + '][tree_species_id]" placeholder="Enter ID" class="form-control" /></td><td><input type="text" name="location[' + i + '][tree_id]" placeholder="Tree ID" class="form-control" /></td><td><input type="text" name="location[' + i + '][width_at_breast_height]" placeholder="Enter Width" class="form-control" /></td><td><input type="text" name="location[' + i + '][height]" placeholder="Enter Height" class="form-control" /></td><td><input type="text" name="location[' + i + '][timber_volume]" placeholder="Enter Volume" class="form-control" /></td><td><input type="text" name="location[' + i + '][timber_cubic]" placeholder="Enter Cubic" class="form-control" /></td><td><input type="text" name="location[' + i + '][age]" placeholder="Enter Age" class="form-control" /></td></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr><tr><td colspan="7"><textarea name="location[' + i + '][remark]" placeholder="Enter Remarks" class="form-control" rows="3"></textarea></td></tr>');
  });
  $(document).on('click', '.remove-tr', function() {
    $(this).parents('tr').next('tr').remove()
    $(this).parents('tr').remove();
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
    var data = new FormData(document.getElementById("regForm"));
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
</script>
@endsection