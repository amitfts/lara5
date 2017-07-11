<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of Carpool
 *
 * @author User
 */
class Search extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'searches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['from_loc', 'from_lat', 'from_lng','to_loc', 'to_lat', 'to_lng',];
    
    public static function refineLocation($location) {
        $locationArr = explode(', ', $location);
        array_pop($locationArr); //Removing country
        array_pop($locationArr); //Removing State
        $updatedLocation = implode(',', $locationArr);
        return $updatedLocation;
    }

}
