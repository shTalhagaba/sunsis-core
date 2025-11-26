<?php

namespace App\Services\Address;

use App\Models\Address;

class UserAddressService
{
    public function saveHomeAddress($user, array $address)
    {
        $homeAddress = $user->homeAddress();
        $homeAddress->label = Address::LABEL_HOME;
        $homeAddress->address_line_1 = isset($address['home_address_line_1']) ? $address['home_address_line_1'] : null;
        $homeAddress->address_line_2 = isset($address['home_address_line_2']) ? $address['home_address_line_2'] : null;
        $homeAddress->address_line_3 = isset($address['home_address_line_3']) ? $address['home_address_line_3'] : null;
        $homeAddress->address_line_4 = isset($address['home_address_line_4']) ? $address['home_address_line_4'] : null;
        $homeAddress->postcode = isset($address['home_postcode']) ? strtoupper($address['home_postcode']) : null;
        $homeAddress->telephone = isset($address['home_telephone']) ? $address['home_telephone'] : null;
        $homeAddress->mobile = isset($address['home_mobile']) ? $address['home_mobile'] : null;
        $user->addresses()->save($homeAddress);
    }

    public function saveWorkAddress($user, array $address)
    {
        $workAddress = $user->workAddress();
        $workAddress->label = Address::LABEL_WORK;
        $workAddress->address_line_1 = isset($address['work_address_line_1']) ? $address['work_address_line_1'] : null;
        $workAddress->address_line_2 = isset($address['work_address_line_2']) ? $address['work_address_line_2'] : null;
        $workAddress->address_line_3 = isset($address['work_address_line_3']) ? $address['work_address_line_3'] : null;
        $workAddress->address_line_4 = isset($address['work_address_line_4']) ? $address['work_address_line_4'] : null;
        $workAddress->postcode = isset($address['work_postcode']) ? strtoupper($address['work_postcode']) : null;
        $workAddress->telephone = isset($address['work_telephone']) ? $address['work_telephone'] : null;
        $workAddress->mobile = isset($address['work_mobile']) ? $address['work_mobile'] : null;
        $user->addresses()->save($workAddress);
    }

    public function saveAddresses($user, array $address)
    {
        $this->saveHomeAddress($user, $address);
        $this->saveWorkAddress($user, $address);
    }
}