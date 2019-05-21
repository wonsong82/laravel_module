<?php
namespace Module\Application\Traits;


use Module\Application\Constants\Address;

trait AddressCrudTrait {


    public function addAddressFields($tab = null)
    {
        $fields = [
            [
                'name' => 'physical_address_attention',
                'label' => __('application::address.attention'),
                'attributes' => [
                    'placeholder' => __('application::address.attention')
                ],
            ],
            [
                'name' => 'physical_address_line1',
                'label' => __('application::address.line1'),
                'attributes' => [
                    'placeholder' => __('application::address.line1')
                ],
            ],
            [
                'name' => 'physical_address_line2',
                'label' => __('application::address.line2'),
                'attributes' => [
                    'placeholder' => __('application::address.line2')
                ],
            ],
            [
                'name' => 'physical_address_line3',
                'label' => __('application::address.line3'),
                'attributes' => [
                    'placeholder' => __('application::address.line3')
                ],
            ],
            [
                'name' => 'physical_address_line4',
                'label' => __('application::address.line4'),
                'attributes' => [
                    'placeholder' => __('application::address.line4')
                ],
            ],
            [
                'name' => 'physical_address_city',
                'label' => __('application::address.city'),
                'attributes' => [
                    'placeholder' => __('application::address.city')
                ],
            ],
            [
                'name' => 'physical_address_state',
                'label' => __('application::address.state'),
                'attributes' => [
                    'placeholder' => __('application::address.state')
                ],
            ],
            [
                'name' => 'physical_address_zip',
                'label' => __('application::address.zip'),
                'attributes' => [
                    'placeholder' => __('application::address.zip')
                ],
            ],
            [
                'name' => 'physical_address_country',
                'label' => __('application::address.country'),
                'type' => 'select2_from_array',
                'options' => Address::getCountryOptions(),
                'attributes' => [
                    'placeholder' => __('application::address.country')
                ],
            ],
            [
                'name' => 'shipping_address_attention',
                'label' => __('application::address.attention'),
                'attributes' => [
                    'placeholder' => __('application::address.attention')
                ],
            ],
            [
                'name' => 'shipping_address_line1',
                'label' => __('application::address.line1'),
                'attributes' => [
                    'placeholder' => __('application::address.line1')
                ],
            ],
            [
                'name' => 'shipping_address_line2',
                'label' => __('application::address.line2'),
                'attributes' => [
                    'placeholder' => __('application::address.line2')
                ],
            ],
            [
                'name' => 'shipping_address_line3',
                'label' => __('application::address.line3'),
                'attributes' => [
                    'placeholder' => __('application::address.line3')
                ],
            ],
            [
                'name' => 'shipping_address_line4',
                'label' => __('application::address.line4'),
                'attributes' => [
                    'placeholder' => __('application::address.line4')
                ],
            ],
            [
                'name' => 'shipping_address_city',
                'label' => __('application::address.city'),
                'attributes' => [
                    'placeholder' => __('application::address.city')
                ],
            ],
            [
                'name' => 'shipping_address_state',
                'label' => __('application::address.state'),
                'attributes' => [
                    'placeholder' => __('application::address.state')
                ],
            ],
            [
                'name' => 'shipping_address_zip',
                'label' => __('application::address.zip'),
                'attributes' => [
                    'placeholder' => __('application::address.zip')
                ],
            ],
            [
                'name' => 'shipping_address_country',
                'label' => __('application::address.country'),
                'type' => 'select2_from_array',
                'options' => Address::getCountryOptions(),
                'attributes' => [
                    'placeholder' => __('application::address.country')
                ],
            ],
            [
                'name' => 'billing_address_attention',
                'label' => __('application::address.attention'),
                'attributes' => [
                    'placeholder' => __('application::address.attention')
                ],
            ],
            [
                'name' => 'billing_address_line1',
                'label' => __('application::address.line1'),
                'attributes' => [
                    'placeholder' => __('application::address.line1')
                ],
            ],
            [
                'name' => 'billing_address_line2',
                'label' => __('application::address.line2'),
                'attributes' => [
                    'placeholder' => __('application::address.line2')
                ],
            ],
            [
                'name' => 'billing_address_line3',
                'label' => __('application::address.line3'),
                'attributes' => [
                    'placeholder' => __('application::address.line3')
                ],
            ],
            [
                'name' => 'billing_address_line4',
                'label' => __('application::address.line4'),
                'attributes' => [
                    'placeholder' => __('application::address.line4')
                ],
            ],
            [
                'name' => 'billing_address_city',
                'label' => __('application::address.city'),
                'attributes' => [
                    'placeholder' => __('application::address.city')
                ],
            ],
            [
                'name' => 'billing_address_state',
                'label' => __('application::address.state'),
                'attributes' => [
                    'placeholder' => __('application::address.state')
                ],
            ],
            [
                'name' => 'billing_address_zip',
                'label' => __('application::address.zip'),
                'attributes' => [
                    'placeholder' => __('application::address.zip')
                ],
            ],
            [
                'name' => 'billing_address_country',
                'label' => __('application::address.country'),
                'type' => 'select2_from_array',
                'options' => Address::getCountryOptions(),
                'attributes' => [
                    'placeholder' => __('application::address.country')
                ],
            ]
        ];


        if($tab){
            $fields = array_map(function($field) use ($tab){
                if(strpos($field['name'], 'physical_address_') === 0){
                    $field['tab'] = $tab->physical_address;
                }
                elseif(strpos($field['name'], 'shipping_address_') === 0){
                    $field['tab'] = $tab->shipping_address;
                }
                elseif(strpos($field['name'], 'billing_address_') === 0){
                    $field['tab'] = $tab->billing_address;
                }

                return $field;
            }, $fields);
        }


        $this->crud->addFields($fields);
    }    







}
