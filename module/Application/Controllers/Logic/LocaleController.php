<?php
namespace Module\Application\Controllers\Logic;


use Module\Application\Controllers\Logic\LogicController;
use Module\Application\ModelChangeCollection;
use Module\Application\Locale;
use Module\Application\Events\LocaleCreated;
use Module\Application\Events\LocaleDeleted;
use Module\Application\Events\LocaleUpdated;


class LocaleController extends LogicController
{   
    /**
     * @param $params [*code, *country_code, *language_code, *country_name, *language_name]
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($params)
    {
        // create 
        $locale = Locale::create($params);


        // events
        event(new LocaleCreated($locale));


        return $locale;
    }


    /**
     * @param Locale $locale
     * @param $params [*code, *country_code, *language_code, *country_name, *language_name]
     * @return mixed
     */
    public function update(Locale $locale, $params)
    {
        // changes
        $changes = app(ModelChangeCollection::class);
        $changes['locale'] = $locale->getModelChanges($params);
        $changes->checkChanges();


        // update
        $changes->save();


        // events
        event(new LocaleUpdated($locale, $changes));


        return $changes['locale']->model;
    }


    /**
     * @param Locale $locale
     * @return Locale
     */
    public function delete(Locale $locale)
    {
        // delete
        $locale->delete();


        // events
        event(new LocaleDeleted($locale));


        return $locale;
    }

    
}
