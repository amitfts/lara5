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
    @if(isset($from) && isset($to))
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Carpool Result:{{count($carpools)}} </div>
                <div class="panel-body  table-responsive">
                    @if(count($carpools)>0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Start Time</th>
                                <th>Return Time</th>
                                <th>Driver/Passenger</th>
                                <th>Posted On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($carpools))
                            @foreach($carpools as $car)
                            <tr itemscope itemtype="http://schema.org/TravelAction" >
                                <?php
                                $key = '';
                                if ($car->pool_type == 'O') {
                                    $key = 'onetime';
                                    $keyMsg = 'One Time in Rs.' . $car->price;
                                } elseif ($car->regpart2 == null || $car->regpart2 == 0) {
                                    $keyMsg = $key = 'regular';
                                } elseif ($car->regpart2 % 2 === 1) {
                                    $keyMsg = $key = 'odd';
                                } else {
                                    $keyMsg = $key = 'even';
                                }
                                ?>
                                <td colspan="2" >
                                    <a href="{{url('/'.$key.'-carpool-'. $car->id.'-from-'.urlencode(str_replace('-','_',strtolower($car->from_location))).'-to-'.urlencode(str_replace('-','_',strtolower($car->to_location))))}}" title="{{$key}} carpool from {{$car->from_location}} to {{$car->to_location}}" itemprop="name">
                                        <p>
                                        <span itemprop="fromLocation" style="font-weight: bold;">{{$car->from_location}}</span> to
                                        <span itemprop="toLocation" style="font-weight: bold;">{{$car->to_location}}</span> 
                                    </p>
                                    </a>
                                    <div>{{substr($car->details,0,100)}} ..</div>
                                </td>

                                <td itemprop="startTime" @if($car->journey_date) colspan="2" @endif>{{date('h:i A',strtotime($car->start_time))}} {{$car->journey_date}}</td>
                                @if(!$car->journey_date) <td itemprop="endTime"> {{date('h:i A',strtotime($car->return_time))}} </td> @endif

                                <td >


                                    @if($car->user_type=='D')
                                    Driver
                                    @elseif($car->user_type=='P')
                                    Passenger
                                    @else
                                    Both
                                    @endif

                                </td>
                                
                                <td>
                                        {{date('d-M-Y',strtotime($car->created_at))}}
                                   
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        

                    </table>

                    @else
                    <h3>No Result can be found</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
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
        var frmTxt = $('#frmtxt');
        var toTxt = $('#totxt');
        if (frmTxt == toTxt) {
            alert('From and to address should be different')
            return false;
        }
        if ($('#fromlat').val() == '' || $('#tolat').val() == '') {
            alert('Please select location from autosuggest only');
            return false;
        }

        return true;
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete&key=AIzaSyCy9tzYYCoylo9exAox9v-mzD4oOkvQh98"
        async defer>
</script>

@endsection