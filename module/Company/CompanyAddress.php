<?php

namespace Module\Company;

use Illuminate\Database\Eloquent\Model;
use Module\Application\Traits\HasAddresses;
use Module\Application\Traits\HasConstants;
use Module\Application\Traits\HasModelChanges;
use Module\Company\Traits\HasCompany;

class CompanyAddress extends Model
{
    use HasAddresses, HasConstants, HasModelChanges, HasCompany;


    protected $table = 'company_addresses';
    protected $fillable = [
        'company_id',
        'type_code',
        //type
        'attention',
        'line1',
        'line2',
        'line3',
        'line4',
        'city',
        'state',
        'zip',
        'country'
    ];



    // RELATIONS



}
