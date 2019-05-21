<?php

Route::group(['prefix' => 'test'], function(){
    Route::get('react', 'ReactController@index');
    Route::get('test-socket-event', function(){
        event(new App\Events\TestSocketEvent());
    });

    Route::post('/api/input', function(){
        $data = request()->get('data');
        event(new App\Events\TestInputEvent($data));
    });


});


Route::get('test', function(){
    return view('test');
});

Route::get('test/popup', function(){
    return view('popup');
});
Route::post('test/popup', function(){
    $text = request()->get('text');
    return view('popup-next', compact('text'));
});
Route::get('test/ajax', function(){

});


Route::get('webpacktest', function(){
    $js = 'application.js';
    $css = 'application.css';
    return view('layout.blank', compact('js','css'));
});


/*
Route::get('test', function (){


    $a = \Module\Item\Item::all();


    $i = 0;

    foreach($a as $item){
        $item->vendors()->attach(\Module\Vendor\Vendor::class,
            [
                'item_id' => $item->id,
                'vendor_id' => 2,
                'is_default' => TRUE,
                'price' => 100,
                'last_price_date' => now(),
                'lead_time' => 2
            ]);
    }

    dd('done');



    $a = new \Module\Vendor\Vendor();


    $a = $a->items()->get();


    dd($a);


    $a = new \Module\Item\Item();

    $a = $a->all()->pluck('code')->toArray();
//adasd

//    $a =\Module\Production\ItemProcessType::all()->pluck('name', 'name')->all();



//    $a= date("Ymd",strtotime ( '1 day' , strtotime ( date("Ymd") ) ));
    dd($a);
});
*/


