@extends('app')

@section('content')
<ol class="breadcrumb" style="margin-bottom: 5px;">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li class="active">Search</li>
</ol>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Search Carpools</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" onsubmit="return validateCarpool();" >

                        <div class="row form-group">
                            <div class="col-sm-5 col-xs-10 " style="margin:10px " >
                                <input type="text" class="form-control" placeholder="From Location" id="fromtxt" name="from" value="@if(isset($from)){{ $from }}@endif" required />
                                <input type="hidden" id="fromlat" name="fromlat" value="@if(isset($fromlat)){{ $fromlat }}@endif" />
                                <input type="hidden" id="fromlng" name="fromlng" value="@if(isset($fromlng)){{ $fromlng }}@endif" />

                            </div>
                            <div class="col-sm-5 col-xs-10  " style="margin:10px ">
                                <input type="text" class="form-control" placeholder="To Location" id="totxt" name="to" value="@if(isset($to)){{ $to }}@endif" required />
                                <input type="hidden" id="tolat" name="tolat" value="@if(isset($tolat)){{ $tolat }}@endif" />
                                <input type="hidden" id="tolng" name="tolng" value="@if(isset($tolng)){{ $tolng }}@endif" />
                            </div>

                            <div class="col-sm-1 col-xs-5 " style="margin:10px ">
                                <button type="submit" class="btn btn-primary ">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
   
    @include('carpool.nearbytable')
    
</div>
<script>
    var placeSearch, autocomplete, autocomplete2;


    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('fromtxt')),
                {types: ['geocode']});
        autocomplete2 = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('totxt')),
                {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
        autocomplete2.addListener('place_changed', fillInAddress2);
    }

// [START region_fillform]
    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        $('#fromlat').val(place.geometry.location.lat());
        $('#fromlng').val(place.geometry.location.lng());

    }

    function fillInAddress2() {
        // Get the place details from the autocomplete object.
        var place = autocomplete2.getPlace();
        $('#tolat').val(place.geometry.location.lat());
        $('#tolng').val(place.geometry.location.lng());

    }

    function validateCarpool() {
        var frmTxt = $('#fromtxt');
        var toTxt = $('#totxt');
        if (frmTxt.val() == toTxt.val()) {
            alert('From and to address should be different')
            return false;
        }
        if ($('#fromlat').val() == '' || $('#tolat').val() == '') {
            alert('Please select location from autosuggest only');
            return false;
        }
        var frmLoc = removeState(frmTxt.val());
        var toLoc = removeState(toTxt.val());
        frmTxt.val(frmLoc);
        toTxt.val(toLoc);
        return true;
    }
    
    function removeState(txt){
        var arr = txt.split(', ');
        if(arr.length>2 && arr[arr.length-1]=='India'){
            arr.pop();
            arr.pop();
        }
        var newTxt = arr.join(', ');
        return newTxt;
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete&key=AIzaSyCy9tzYYCoylo9exAox9v-mzD4oOkvQh98"
        async defer>
</script>

@endsection