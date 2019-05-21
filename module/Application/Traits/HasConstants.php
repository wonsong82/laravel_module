<?php
namespace Module\Application\Traits;

use Module\Application\Constant;

trait HasConstants {

    /***
     * If $name_code exists, return Constant instance
     *
     * @param $name
     * @return Constant | object | null
     */
    public function __get($name)
    {
        $value = parent::__get($name);
        if($value) return $value;

        $constantFieldName = $name . '_code';
        $value = $this->parseConstantFromFieldName($constantFieldName);
        if($value) return $value;

        $value = null;
        if(preg_match('#_label$#', $name)){
            $constantFieldName = str_replace('_label', '', $name) . '_code';
            $value = $this->parseConstantFromFieldName($constantFieldName);
            if($value){
                $value = '<span class="label label-' . $value->type . '">' . __($value->key) . '</span>';
            }
        }
        if($value) return $value;

        $value = null;
        if(preg_match('#_code_text$#', $name)){
            $constantFieldName = str_replace('_text', '', $name);
            $value = $this->parseConstantFromFieldName($constantFieldName);
            if($value){
                $value = __($value->key);
            }
        }
        if($value) return $value;


        return parent::__get($name);
    }


    protected function parseConstantFromFieldName($name)
    {
        if(isset($this->attributes[$name])){
            $code = $this->attributes[$name];
            return Constant::find($code);
        }

        return null;
    }



}
