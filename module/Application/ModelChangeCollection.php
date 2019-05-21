<?php
namespace Module\Application;



use Illuminate\Support\Collection;
use Module\Application\Exceptions\NotChangedException;

class ModelChangeCollection extends Collection
{
    protected $changed = null;


    /**
     * Iterate and save all ModelChanges
     *
     * @return bool
     */
    public function save()
    {
        if(!$this->changed)
            return false;

        foreach($this as $modelChange){
            $modelChange->save();
        }

        return true;
    }


    /***
     * Check if any model in the collection has any change
     *
     * @return bool
     */
    public function hasAnyChanges()
    {
        $changed = false;

        foreach($this as $change){
            if($change->changed) {
                $changed = true;
                break;
            }
        }

        $this->changed = $changed;

        return $changed;
    }


    /***
     * Check if contains any changes, and throw exception if not
     *
     * @throws NotChangedException
     */
    public function checkChanges()
    {
        if($this->changed === null){
            $this->hasAnyChanges();
        }

        if(!$this->changed){
            throw new NotChangedException();
        }
    }


}