<?php

namespace App\Observers;

use App\Models\Address;

class AddressObserver
{
    public function saving(Address $address)
    {
    	if($address->postcode != '')
    	{
	    	if(!\Postcode::validate($address->postcode))
	        {
	            throw new \Exception('Invalid Postcode: ' . $address->postcode);
	        }
	        $result = \Postcode::postcodeLookup($address->postcode);
	        if($result->status != 200)
	        {
	            abort($result->status);
	        }
	        $address->longitude = $result->result->longitude;
	        $address->latitude = $result->result->latitude;
    	}
    }
}
