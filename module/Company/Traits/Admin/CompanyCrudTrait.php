<?php

namespace Module\Company\Traits\Admin;



use Module\Company\Company;

trait CompanyCrudTrait
{
    protected $company = null;


    /***
     * add this within setup() after setModel and Route before addColumns and Fields
     */
    public function setupCompanyCrud()
    {
        $company = $this->getCompanyFromAuth();
        // Column
        if(!$company){
            $this->crud->addColumn([
                'name' => 'company_id',
                'label' => 'Company',
                'type' => 'select',
                'model' => Company::class,
                'entity' => 'company',
                'attribute' => 'name'
            ])->afterColumn('row_number');
        }


        if($company){
            $this->crud->addField([
                'name' => 'company_id',
                'type' => 'hidden',
                'value' => $company->id
            ]);
        }
        else {
            $this->crud->addField([
                'name' => 'company_id',
                'label' => 'Company',
                'type' => 'select2',
                'model' => Company::class,
                'entity' => 'company',
                'attribute' => 'name'
            ], 'create');
            $this->crud->addField([
                'name' => 'company_id',
                'type' => 'hidden'
            ], 'update');
        }


        if($company){
            $tableName = $this->crud->model->getTable();
            $this->crud->query->where($tableName.'.company_id', $company->id);
        }


        $this->company = $company;

        return $company;

    }


    public function getCompanyFromRequest($req)
    {
        return Company::findOrFail($req['company_id']);
    }


    public function getCompanyFromAuth()
    {
        if(!!($user = auth()->user()))
            return $user->company;
    }






}
