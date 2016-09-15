@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>Popular cities for Carpool</h1>
                </div>
                <div class="panel-body">
                    <div class="col-lg-3 col-md-4 col-sm-6   col-xs-12   home-locations" itemscope="" itemtype="http://schema.org/Place">
                        <a href="http://www.sameroute.in/carpool-from-Greater%20Noida" title="Carpool from Greater Noida"><span itemprop="name">Greater Noida</span></a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6   col-xs-6  home-locations" itemscope="" itemtype="http://schema.org/Place">
                        <a href="http://www.sameroute.in/carpool-from-Noida" title="Carpool from Noida"><span itemprop="name">Noida</span></a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6   col-xs-6  home-locations" itemscope="" itemtype="http://schema.org/Place">
                        <a href="http://www.sameroute.in/carpool-from-Ghaziabad" title="Carpool from Ghaziabad"><span itemprop="name">Ghaziabad</span></a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6   col-xs-12   home-locations" itemscope="" itemtype="http://schema.org/Place">
                        <a href="http://www.sameroute.in/carpool-from-New%20Delhi" title="Carpool from New Delhi"><span itemprop="name">New Delhi</span></a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6   col-xs-6  home-locations" itemscope="" itemtype="http://schema.org/Place">
                        <a href="http://www.sameroute.in/carpool-from-Faridabad" title="Carpool from Faridabad"><span itemprop="name">Faridabad</span></a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6   col-xs-6  home-locations" itemscope="" itemtype="http://schema.org/Place">
                        <a href="http://www.sameroute.in/carpool-from-Gurgaon" title="Carpool from Gurgaon"><span itemprop="name">Gurgaon</span></a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6   col-xs-6  home-locations" itemscope="" itemtype="http://schema.org/Place">
                        <a href="http://www.sameroute.in/carpool-from-Pune" title="Carpool from Pune"><span itemprop="name">Pune</span></a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6   col-xs-6  home-locations" itemscope="" itemtype="http://schema.org/Place">
                        <a href="http://www.sameroute.in/carpool-from-Chennai" title="Carpool from Chennai"><span itemprop="name">Chennai</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">Why sameroute.in</div>

                <div class="panel-body">
                    Our aim is to save environment and petrol. 
                    We have provided a tool to find people who are traveling in to the same route.
                    Find people who are in to the www.sameroute.in. It will be a great pleasure to us if you find companions.
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Recent carpools</div>
                    <div class="panel-body table-responsive">
                        @include('carpool.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection