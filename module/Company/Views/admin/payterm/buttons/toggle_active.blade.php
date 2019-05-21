@php
    if($entry->status_code == \Module\Company\Constants\PaytermStatus::ACTIVE){
        $url = route('company::crud.payterm.deactivate', ['payterm' => $entry->id]);
        $text = __('company::payterm.field.deactivate');
    }
    else {
    $url = route('company::crud.payterm.activate', ['payterm' => $entry->id]);
        $text = __('company::payterm.field.activate');
    }
@endphp
<a class="btn btn-xs btn-primary" href="{{$url}}">{{$text}}</a>