<table class="table table-striped">
    <thead>
        <tr>
            <th>From</th>
            <th>To</th>
            <th>Start Time</th>
            <th>Return Time</th>
            <th>Even/Odd</th>
            <th>Driver/Passenger</th>
        </tr>
    </thead>
    <tbody>
        @if(count($carpools))
        @foreach($carpools as $car)
        <tr itemscope itemtype="http://schema.org/TravelAction" >
            <td colspan="2" ><p><span itemprop="fromLocation" style="font-weight: bold;">{{$car->from_location}}</span> to
                    <span itemprop="toLocation" style="font-weight: bold;">{{$car->to_location}}</span> </p>
                <div>{{substr($car->details,0,100)}} ..</div>
            </td>

            <td itemprop="startTime" @if($car->journey_date) colspan="2" @endif>{{date('h:i A',strtotime($car->start_time))}} {{$car->journey_date}}</td>

            @if(!$car->journey_date) <td itemprop="endTime"> {{date('h:i A',strtotime($car->return_time))}} </td> @endif
            <td><?php
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
                {{ucfirst($keyMsg)}}
            </td>
            <td >

                <a href="{{url('/'.$key.'-carpool-'. $car->id.'-from-'.urlencode(str_replace('-','_',strtolower($car->from_location))).'-to-'.urlencode(str_replace('-','_',strtolower($car->to_location))))}}" title="{{$key}} carpool from {{$car->from_location}} to {{$car->to_location}}" itemprop="name">
                    @if($car->user_type=='D')
                    Driver
                    @elseif($car->user_type=='P')
                    Passenger
                    @else
                    Both
                    @endif
                </a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">{!! $carpools->render() !!}</td>
        </tr>

    </tfoot>

</table>
