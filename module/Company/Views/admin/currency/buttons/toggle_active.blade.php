@php
if($entry->status_code == \Module\Company\Constants\CurrencyStatus::ACTIVE){
    $url = route('company::crud.currency.deactivate', ['currency' => $entry->id]);
    $text = __('company::currency.field.deactivate');
}
else {
$url = route('company::crud.currency.activate', ['currency' => $entry->id]);
    $text = __('company::currency.field.activate');
}
@endphp
<a class="btn btn-xs btn-primary" href="{{$url}}">{{$text}}</a>