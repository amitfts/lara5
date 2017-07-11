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
                                <a href="{{url('/'.$key.'-carpool-'. $car->id.'-from-'.urlencode(str_replace('-','_',strtolower($car->from_location))).'-to-'.urlencode(str_replace('-','_',strtolower($car->to_location))))}}" title="{{$key}} carpool from {{$car->from_location}} to {{$car->to_location}}">
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
                <h3>There is no carpools with matching criteria</h3>
                @endif
            </div>
        </div>
    </div>
</div>
@endif