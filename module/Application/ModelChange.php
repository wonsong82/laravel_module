<?php
namespace Module\Application;



class ModelChange
{
    public $type;
    public $relation;
    public $model;
    public $changes;
    public $changed;

    public function __construct($type, $relation, $model, $changes, $changed)
    {
        $this->type = $type;
        $this->relation = $relation;
        $this->model = $model;
        $this->changes = $changes;
        $this->changed = $changed;
    }


    public function save()
    {
        if(!$this->changed){
            return false;
        }

        switch($this->type){

            case 'model':
                $this->saveModelChange();
                break;

            case 'pivot':
                $this->savePivotChange();
                break;

            case 'relation':
                $this->saveRelationChange();
                break;
        }
    }



    public function saveModelChange()
    {
        $this->model->save();
    }



    public function savePivotChange()
    {
        $relation = $this->relation;

        foreach($this->changes as $change){
            switch($change['type']){

                case 'create':
                    $this->model->$relation()->attach($change['model']->id);
                    break;

                case 'delete':
                    $this->model->$relation()->detach($change['model']->id);
                    break;
            }
        }
    }



    public function saveRelationChange()
    {
        $relation = $this->relation;

        foreach($this->changes as $change){
            switch($change['type']){

                case 'create':
                    $this->model->$relation()->save($change['model']);
                    break;

                case 'update':
                    $change['model']->save();
                    break;

                case 'delete':
                    $change['model']->delete();
                    break;
            }
        }
    }

}