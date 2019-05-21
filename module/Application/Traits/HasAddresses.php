<?php
namespace Module\Application\Traits;

use Module\Application\Constants\AddressType;


trait HasAddresses {



    // FUNCTIONS FOR MODELS THAT HAS ADDRESSES

    public function getAddressAttribute()
    {
        return $this
            ->addresses()
            ->where('type_code', AddressType::PHYSICAL)
            ->first();
    }

    public function getBillingAddressAttribute()
    {
        return $this
            ->addresses()
            ->where('type_code', AddressType::BILLING)
            ->first();
    }

    public function getShippingAddressAttribute()
    {
        return $this
            ->addresses()
            ->where('type_code', AddressType::SHIPPING)
            ->first();
    }

    public function parseAddressParams($params)
    {
        $addressData = (object)[
            'physical'=>['type_code' => AddressType::PHYSICAL],
            'shipping'=>['type_code' => AddressType::SHIPPING],
            'billing'=>['type_code' => AddressType::BILLING]
        ];

        foreach($params as $key => $value){
            if(preg_match('#^physical_address_#', $key)){
                $addressData->physical[str_replace('physical_address_','',$key)] = $value;
            }
            else if(preg_match('#^shipping_address_#', $key)){
                $addressData->shipping[str_replace('shipping_address_','',$key)] = $value;
            }
            else if(preg_match('#^billing_address_#', $key)){
                $addressData->billing[str_replace('billing_address_','',$key)] = $value;
            }
        }

        return $addressData;
    }




    // FUNCTIONS FOR MODELS THAT ARE ADDRESSES

    public function format($format)
    {
        $matches = null;
        preg_match_all('#\{(.+?)\}#', $format, $matches);

        if(isset($matches[1]) && count($matches[1]) > 0){
            $address = $format;
            foreach($matches[1] as $field){
                $address = str_replace("{{$field}}", $this->$field, $address);
            }
            return $address;
        }

        throw new \Exception('Error while formatting address.');
    }







    public function getAddressTextAttribute()
    {
        return $this->address ? $this->address->format('{line1} {line2}, {city} {state}, {zip} {country}') : '';
    }

    public function getPhysicalAddressTextAttribute()
    {
        $address = $this->address;
        return $this->getLocaleAddress($address);

    }

    public function getBillingAddressTextAttribute()
    {
        $address = $this->billing_address;
        return $this->getLocaleAddress($address);

    }

    public function getShippingAddressTextAttribute()
    {
        $address = $this->shipping_address;
        return $this->getLocaleAddress($address);
    }

    protected function getLocaleAddress($address)
    {
        $addressText = '';

        switch($address->country){
            case 'Korea':
                $addressText = $address->format('{country} <br>{state} {city} {line1} <br>{line2} <br>{attention} <br>{zip}');
                break;

            case 'China':
                // todo:
                break;

            default:
                $addressText = $address->format('{attention} <br>{line1} {line2} {line3} {line4} <br>{city} {state}, {zip} <br>{country}');
        }

        return trim(preg_replace('#[\s]{2,}#', ' ', $addressText));
    }




    public function parseAddressRequests()
    {
        $addressReq = (object)[
            'physical'=>['type_code' => AddressType::PHYSICAL],
            'shipping'=>['type_code' => AddressType::SHIPPING],
            'billing'=>['type_code' => AddressType::BILLING]
        ];

        foreach(request()->all() as $key=>$value){
            if(preg_match('#^physical_address_#', $key)){
                $addressReq->physical[str_replace('physical_address_','',$key)] = $value;
            }
            else if(preg_match('#^shipping_address_#', $key)){
                $addressReq->shipping[str_replace('shipping_address_','',$key)] = $value;
            }
            else if(preg_match('#^billing_address_#', $key)){
                $addressReq->billing[str_replace('billing_address_','',$key)] = $value;
            }
        }

        return $addressReq;
    }


    public function getPhysicalAddressAttentionAttribute()
    {
        return $this->address->attention ?? '';
    }
    public function getPhysicalAddressLine1Attribute()
    {
        return $this->address->line1 ?? '';
    }
    public function getPhysicalAddressLine2Attribute()
    {
        return $this->address->line2 ?? '';
    }
    public function getPhysicalAddressLine3Attribute()
    {
        return $this->address->line3 ?? '';
    }
    public function getPhysicalAddressLine4Attribute()
    {
        return $this->address->line4 ?? '';
    }
    public function getPhysicalAddressCityAttribute()
    {
        return $this->address->city ?? '';
    }
    public function getPhysicalAddressStateAttribute()
    {
        return $this->address->state ?? '';
    }
    public function getPhysicalAddressZipAttribute()
    {
        return $this->address->zip ?? '';
    }
    public function getPhysicalAddressCountryAttribute()
    {
        return $this->address->country ?? '';
    }


    public function getShippingAddressAttentionAttribute()
    {
        return $this->shipping_address->attention ?? '';
    }
    public function getShippingAddressLine1Attribute()
    {
        return $this->shipping_address->line1 ?? '';
    }
    public function getShippingAddressLine2Attribute()
    {
        return $this->shipping_address->line2 ?? '';
    }
    public function getShippingAddressLine3Attribute()
    {
        return $this->shipping_address->line3 ?? '';
    }
    public function getShippingAddressLine4Attribute()
    {
        return $this->shipping_address->line4 ?? '';
    }
    public function getShippingAddressCityAttribute()
    {
        return $this->shipping_address->city ?? '';
    }
    public function getShippingAddressStateAttribute()
    {
        return $this->shipping_address->state ?? '';
    }
    public function getShippingAddressZipAttribute()
    {
        return $this->shipping_address->zip ?? '';
    }
    public function getShippingAddressCountryAttribute()
    {
        return $this->shipping_address->country ?? '';
    }


    public function getBillingAddressAttentionAttribute()
    {
        return $this->billing_address->attention ?? '';
    }
    public function getBillingAddressLine1Attribute()
    {
        return $this->billing_address->line1 ?? '';
    }
    public function getBillingAddressLine2Attribute()
    {
        return $this->billing_address->line2 ?? '';
    }
    public function getBillingAddressLine3Attribute()
    {
        return $this->billing_address->line3 ?? '';
    }
    public function getBillingAddressLine4Attribute()
    {
        return $this->billing_address->line4 ?? '';
    }
    public function getBillingAddressCityAttribute()
    {
        return $this->billing_address->city ?? '';
    }
    public function getBillingAddressStateAttribute()
    {
        return $this->billing_address->state ?? '';
    }
    public function getBillingAddressZipAttribute()
    {
        return $this->billing_address->zip ?? '';
    }
    public function getBillingAddressCountryAttribute()
    {
        return $this->billing_address->country ?? '';
    }








}
