<?php
namespace Module\Company\Traits;


use Module\Company\Company;

trait HasCompany {


    /**
     * @param $query
     * @param null $company [null: get company from auth | companyId | Company $company]
     */
    public function scopeInCompany($query, $company = null)
    {
        if(!$company){
            $company = auth()->user()->company;
        }
        elseif(!$company instanceof Company){
            $company = Company::findOrFail($company);
        }

        $query->where('company_id', $company->id);
    }



    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}