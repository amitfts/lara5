<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Carpool;
use App\Contact;
use App\Search;
use DB;

class HomeController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Home Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders your application's "dashboard" for users that
      | are authenticated. Of course, you are free to change or remove the
      | controller as you wish. It is just here to get your app started!
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(Request $request) {
        $carpools = Carpool::orderBy('id', 'desc')->paginate(20);
        $page = trim($request->get('page'));
        $extraTitle = '';
        if ($page > 1) {
            $first = $carpools->first();
            $extraTitle = $first->from_location . ' to ' . $first->to_location . ' at page ' . $page;
        }
        $view = [
            'title' => 'Carpooling rideshare ' . $extraTitle,
            'metaKey' => "sameroute, Carpool, rideshare",
            'metaDesc' => 'Search and share carpool rideshare ',
            'carpools' => $carpools
        ];
        return view('home', $view);
    }

    public function fromLocation($locality) {
        $locCount = $location = Location::where('locality', $locality)->count();
        if ($locCount == 1) {
            $location = Location::where('locality', $locality)->first();
        } else {
            $location = Location::where('district', $locality)->first();
        }

        if (is_object($location) && is_int($location->id)) {
            $fromLoc = DB::table('carpools')
                    ->join('locations', 'carpools.to_location_id', '=', 'locations.id')
                    ->where('carpools.from_location_id', $location->id)
                    ->select('locations.locality', 'locations.district')
                    ->groupBy('carpools.to_location_id')
                    ->get();

            $view = [
                'title' => 'Carpool from ' . $location->getFinalLocality(),
                'metaKey' => 'carpool from ' . $location->locality . ', rideshare in ' . $location->locality . ', ',
                'metaDesc' => 'carpool from ' . $location->locality . '. ',
                'location' => $location, 'fromLoc' => $fromLoc
            ];
            return view('carpool.from', $view);
        } else {
            abort(404, 'Page not found');
        }
    }

    public function inLocation($locality) {
        $locCount = $location = Location::where('locality', $locality)->count();
        if ($locCount == 1) {
            $location = Location::where('locality', $locality)->first();
        } else {
            $location = Location::where('district', $locality)->first();
        }

        if (is_object($location) && is_int($location->id)) {
            $fromLoc = DB::table('carpools')
                    ->join('locations', 'carpools.to_location_id', '=', 'locations.id')
                    ->where('carpools.from_location_id', $location->id)
                    ->select('locations.locality', 'locations.district')
                    ->groupBy('carpools.to_location_id')
                    ->get();
            $toLoc = DB::table('carpools')
                    ->join('locations', 'carpools.from_location_id', '=', 'locations.id')
                    ->where('carpools.to_location_id', $location->id)
                    ->select('locations.locality', 'locations.district')
                    ->groupBy('carpools.from_location_id')
                    ->get();

            $view = [
                'title' => 'Carpool from and to  ' . $location->locality,
                'metaKey' => 'carpool from and to  ' . $location->locality . ', rideshare in ' . $location->locality . ', ',
                'metaDesc' => 'carpool from and to  ' . $location->locality . '. ',
                'location' => $location, 'fromLoc' => $fromLoc, 'toLoc' => $toLoc
            ];
            return view('carpool.fromto', $view);
        }
    }

    public function fromToLocation($from, $to) {
        $fromLocCnt = Location::where('locality', $from)->count();
        if ($fromLocCnt == 1) {
            $fromLoc = Location::where('locality', $from)->first();
        } else {
            $fromLoc = Location::where('district', $from)->first();
        }
        $toLocCnt = Location::where('locality', $to)->count();
        if ($toLocCnt == 1) {
            $toLoc = Location::where('locality', $to)->first();
        } else {
            $toLoc = Location::where('district', $to)->first();
        }

        if (is_object($toLoc) && is_int($toLoc->id) && is_object($fromLoc) && is_int($fromLoc->id)) {
            $carpools = Carpool::where('from_location_id', $fromLoc->id)
                    ->where('to_location_id', $toLoc->id)
                    ->orderBy('id', 'desc')
                    ->paginate(20);
            $strFrmTo = 'from ' . $fromLoc->getFinalLocality() . ' to ' . $toLoc->getFinalLocality();
            $view = [
                'title' => 'Carpool ' . $strFrmTo,
                'metaKey' => 'carpool ' . $strFrmTo . ', rideshare ' . $strFrmTo . ', ',
                'metaDesc' => 'carpool ' . $strFrmTo . '. ',
                'carpools' => $carpools,
                'fromLoc' => $fromLoc->getFinalLocality()
            ];
            return view('carpool.list', $view);
        } else {
            abort(404, 'Page not found');
        }
    }

    public function details($carpoolId, $from, $to) {
        $carpool = Carpool::find($carpoolId);
        if ($carpool && strtolower($carpool->from_location) == urldecode(str_replace('_', '-', $from))) {
            $strFrmTo =  'from ' . $carpool->from_location . ' to ' . $carpool->to_location;
            $name = $carpool->user->name;
            $key = '';

            $view = [
                'title' => 'Carpool ' . $strFrmTo . ' ' . $carpool->id,
                'metaKey' => 'carpool ' . $strFrmTo . ' by ' . $name . ', rideshare ' . $strFrmTo . ', ',
                'metaDesc' => $carpool->details,
                'carpool' => $carpool
            ];
            if ($carpool->from_lat && $carpool->from_lng && $carpool->to_lat && $carpool->to_lng) {

                $q = "SELECT *,
                (6371 * acos( cos( radians($carpool->from_lat) ) * cos( radians( from_lat ) ) * cos( radians( $carpool->from_lng ) - radians(from_lng) ) + sin( radians($carpool->from_lat) ) * sin( radians(from_lat) ) )) AS d1,
                (6371 * acos( cos( radians($carpool->from_lat) ) * cos( radians( to_lat) ) * cos( radians( $carpool->from_lng ) - radians(to_lng) ) + sin( radians($carpool->from_lat) ) * sin( radians(to_lat) ) )) AS d2,
                (6371 * acos( cos( radians(to_lat) ) * cos( radians( from_lat ) ) * cos( radians( to_lng ) - radians(from_lng) ) + sin( radians(to_lat) ) * sin( radians(from_lat) ) )) AS a,
                (6371 * acos( cos( radians($carpool->to_lat) ) * cos( radians( from_lat ) ) * cos( radians( $carpool->to_lng ) - radians(from_lng) ) + sin( radians($carpool->to_lat) ) * sin( radians(from_lat) ) )) AS d3,
                (6371 * acos( cos( radians($carpool->to_lat) ) * cos( radians( to_lat) ) * cos( radians( $carpool->to_lng ) - radians(to_lng) ) + sin( radians($carpool->to_lat) ) * sin( radians(to_lat) ) )) AS d4
                FROM  `carpools` 
                where id<> $carpool->id
                Having (
                    ( d1+ d2) < (case when(a<30 ) then ( 1.5 * a) else (1.25*a) end )
                    AND
                    (d3 + d4) < (case when(a<30 ) then ( 1.5 * a) else (1.25*a) end )
                    and 
                    (d1 < d3)
                )
                order by (d1+d4) limit 10";
                $carpools = DB::select($q, []);
                $view['from'] = $carpool->from_location;
                $view['to'] = $carpool->to_location;
                $view['carpools'] = $carpools;
            }
            return view('carpool.details', $view);
        } else {
            abort(404, 'Page not found');
        }
    }

    public function search(Request $request) {
        $from = trim($request->get('from'));
        $to = trim($request->get('to'));
        $fromlat = trim($request->get('fromlat'));
        $fromlng = trim($request->get('fromlng'));
        $tolat = trim($request->get('tolat'));
        $tolng = trim($request->get('tolng'));

        $view = [
            'title' => 'Carpool Search',
            'metaKey' => 'Search Carpool and people on same route, ',
            'metaDesc' => 'Search carpool and people who are traveling in same route. ',
        ];


        if (filter_var($fromlat, FILTER_VALIDATE_FLOAT) &&
                filter_var($fromlng, FILTER_VALIDATE_FLOAT) &&
                filter_var($tolat, FILTER_VALIDATE_FLOAT) &&
                filter_var($tolng, FILTER_VALIDATE_FLOAT)) {
            $view = [
                'title' => 'Carpool Search from ' . $from . ' to ' . $to,
                'metaKey' => 'Carpool from ' . $from . ' to ' . $to . ', ',
                'metaDesc' => 'People who are travel from ' . $from . ' to ' . $to . ' or near by locations. ',
            ];
            $q = "SELECT *,
(6371 * acos( cos( radians($fromlat) ) * cos( radians( from_lat ) ) * cos( radians( $fromlng ) - radians(from_lng) ) + sin( radians($fromlat) ) * sin( radians(from_lat) ) )) AS d1,
(6371 * acos( cos( radians($fromlat) ) * cos( radians( to_lat) ) * cos( radians( $fromlng ) - radians(to_lng) ) + sin( radians($fromlat) ) * sin( radians(to_lat) ) )) AS d2,
(6371 * acos( cos( radians(to_lat) ) * cos( radians( from_lat ) ) * cos( radians( to_lng ) - radians(from_lng) ) + sin( radians(to_lat) ) * sin( radians(from_lat) ) )) AS a,
(6371 * acos( cos( radians($tolat) ) * cos( radians( from_lat ) ) * cos( radians( $tolng ) - radians(from_lng) ) + sin( radians($tolat) ) * sin( radians(from_lat) ) )) AS d3,
(6371 * acos( cos( radians($tolat) ) * cos( radians( to_lat) ) * cos( radians( $tolng ) - radians(to_lng) ) + sin( radians($tolat) ) * sin( radians(to_lat) ) )) AS d4
FROM  `carpools` 
Having (
    ( d1+ d2) < (case when(a<30 ) then ( 1.5 * a) else (1.25*a) end )
    AND
    (d3 + d4) < (case when(a<30 ) then ( 1.5 * a) else (1.25*a) end )
    and 
    (d1 < d3)
)
order by (d1+d4) limit 40";
            $carpools = DB::select($q, []);
            $view['carpools'] = $carpools;
            $view['from'] = $from;
            $view['to'] = $to;
            $view['fromlat'] = $fromlat;
            $view['tolat'] = $tolat;
            $view['fromlng'] = $fromlng;
            $view['tolng'] = $tolng;
            if (count($carpools)) {
                if (Search::where('from_loc', $from)->where('to_loc', $to)->count() == 0) {
                    $searchesArr = ['from_loc' => $from, 'from_lat' => $fromlat,
                        'from_lng' => $fromlng,
                        'to_loc' => $to, 'to_lat' => $tolat, 'to_lng' => $tolng
                    ];
                    Search::create($searchesArr);
                }
            }
        }

        return view('carpool.search', $view);
    }

    public function contact() {
        $view = [
            'title' => 'Contact Us',
            'metaKey' => 'Contact Us in sameroute.in, ',
            'metaDesc' => 'Your feedback is helpful to us to improve sameroute.in.Please give us your suggetion. ',
        ];
        return view('carpool.contact', $view);
    }

    public function postContact(Request $request) {
        $name = $request->get('name');
        $email = $request->get('email');
        $mobile = $request->get('mobile');
        $subject = $request->get('subject');
        $message = $request->get('message');
        $arr = ['name' => $name, 'email' => $email, 'mobile' => $mobile, 'subject' => $subject, 'message' => $message];
        Contact::firstOrCreate($arr);
        return response()->json(['status' => true, 'message' => 'Thanks for contact us']);
    }

    public function test() {
        $fromLocation = Location::where('locality', 'New Delhi')->first();
        echo $fromLocation->id;
        die;
    }

    public function upload() {
        die();
        $row = 1;
        $arr = [];
        if (($handle = fopen("locations-delhi.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $arr[] = $data;
            }
            fclose($handle);
        }
        $i = 1;
        $allowedArr = [4, 5, 6, 9, 13];
        $startTime = time();
        foreach ($arr as $k => $val) {
            foreach ($arr as $a => $v) {
                if (trim($val[0]) && trim($val[1]) && $v[0] && $v[1]) {
                    if (($val[1] != $v[1]) && in_array($val[1], $allowedArr)) {
                        $details = 'Post your own carpool to find people who travels from ' . $val[0] . ' to ' . $v[0] . '.';
                        $carArr = [
                            'user_id' => 1,
                            'from_location_id' => $val[1],
                            'from_location' => $val[0],
                            'to_location_id' => $v[1],
                            'to_location' => $v[0],
                            'start_time' => '08:00',
                            'return_time' => '18:00',
                            'details' => $details,
                            'user_type' => 'P',
                            'regpart1' => 'DL ' . ($i % 10) . 'CR',
                            'regpart2' => $i
                        ];
                        $i++;
                        $carpool = Carpool::create($carArr);
                        $carArr = [
                            'user_id' => 1,
                            'from_location_id' => $v[1],
                            'from_location' => $v[0],
                            'to_location_id' => $val[1],
                            'to_location' => $val[0],
                            'start_time' => '09:00',
                            'return_time' => '19:00',
                            'details' => $details,
                            'user_type' => 'P',
                            'regpart1' => 'DL ' . ($i % 10) . 'CR',
                            'regpart2' => $i
                        ];
                        $carpool = Carpool::create($carArr);
                        echo $details . "\n<br>";
                    }
                }
            }
        }
        $endTime = time();
        echo "Total time spend is :" . ($endTime - $startTime);
    }

    public function terms() {
        return view('carpool.terms', []);
    }

    public function cities() {
        $locations = Location::get();
        $view = [
            'title' => 'Cities where Carpooling rideshare available',
            'metaKey' => "sameroute, carpool cities ",
            'metaDesc' => 'Save petrol and money by carpooling in sameroute.in',
            'locations' => $locations
        ];
        return view('carpool.cities', $view);
    }

    function sitemap() {
        $locations = Location::get();
        $locationStr = '';
        foreach ($locations as $loc) {
            $carpool = Carpool::where('from_location_id', $loc->id)->orWhere('to_location_id', $loc->id)->orderBy('id', 'desc')->first();
            if ($carpool) {
                $locationStr .= "<url>
                <loc>http://www.sameroute.in/from-{$loc->locality}</loc>
                <lastmod>" . substr($carpool->created_at, 0, 10) . "</lastmod>
                <changefreq>weekly</changefreq>
                <priority>.8</priority>
            </url>
            ";
            }
        }
        foreach ($locations as $loc) {
            foreach ($locations as $loc1) {
                $carpool = Carpool::where('from_location_id', $loc->id)->Where('to_location_id', $loc1->id)->orderBy('id', 'desc')->first();
                if ($carpool) {
                    $locationStr .= "<url>
                    <loc>http://www.sameroute.in/carpool-from-{$loc->locality}/to-{$loc1->locality}</loc>
                    <lastmod>" . substr($carpool->created_at, 0, 10) . "</lastmod>
                    <changefreq>weekly</changefreq>
                    <priority>.7</priority>
                </url>
                ";
                }
            }
        }
        $searches = Search::all();
        foreach ($searches as $search) {
            $locationStr .= "<url>
                    <loc>http://www.sameroute.in/search?from={$search->from_loc}&to={$search->to_loc}&fromlat={$search->from_lat}&fromlng={$search->from_lng}&tolat={$search->to_lat}&tolng={$search->to_lng}</loc>
                    <lastmod>" . substr($search->created_at, 0, 10) . "</lastmod>
                    <changefreq>weekly</changefreq>
                    <priority>.7</priority>
                </url>
                ";
        }
        $carpool = Carpool::orderBy('id', 'desc')->first();
        $view = [
            'homepage_last_modified' => substr($carpool->created_at, 0, 10),
            'locations' => $locationStr,
            'xmlStart' => "<?xml version='1.0' encoding='UTF-8'?>"
        ];
        return view('carpool.sitemap', $view);
    }

}
