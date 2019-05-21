<?php
namespace Module\Application\Traits;



trait HasPhones {


    /***
     * Return the formatted phone number
     * US number format ONLY
     *
     * @param $format
     * @param $number
     * @return mixed|string
     * @internal param string $fieldName : can be different db fieldName such as fax
     */
    public static function formatPhone($number, $format=null)
    {
        $data = self::parsePhoneNumber($number);

        if(is_null($format))
            $format = '{area}{prefix}{number}';

        return $data->area && $data->prefix && $data->number
            ? str_replace('{country}', $data->country,
                str_replace('{area}', $data->area,
                    str_replace('{prefix}', $data->prefix,
                        str_replace('{number}', $data->number, $format))))
            : '';
    }


    public function formatPhoneNumber($format=null, $field='phone')
    {
        if(!$format){
            $format = '({area}) {prefix} - {number}';
        }

        return self::formatPhone($this->$field, $format);
    }




    /***
     * Parse given number string
     * US number format ONLY
     *
     * @param $number
     * @return object
     */
    public static function parsePhoneNumber($number)
    {
        $num = preg_replace('#[^\d]#', '', trim($number));

        switch(strlen($num)){
            case 11:
                $country = substr($num, 0, 1);
                $area = substr($num, 1, 3);
                $prefix = substr($num, 4, 3);
                $number = substr($num, 7, 4);
                break;
            case 10:
                $country = '1';
                $area = substr($num, 0, 3);
                $prefix = substr($num, 3, 3);
                $number = substr($num, 6, 4);
                break;
            case 7:
                $country = null;
                $area = null;
                $prefix = substr($num, 0, 3);
                $number = substr($num, 3, 4);
                break;
            default:
                $country = null;
                $area = null;
                $prefix = null;
                $number = null;

        }

        return (object)compact('country', 'area', 'prefix', 'number');
    }










}