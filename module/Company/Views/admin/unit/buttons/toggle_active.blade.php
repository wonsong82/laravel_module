@php
    if($entry->status_code == \Module\Company\Constants\UnitStatus::ACTIVE){
        $url = route('company::crud.unit.deactivate', ['unit' => $entry->id]);
        $text = __('company::unit.field.deactivate');
    }
    else {
    $url = route('company::crud.unit.activate', ['unit' => $entry->id]);
        $text = __('company::unit.field.activate');
    }
@endphp
<a class="btn btn-xs btn-primary" href="{{$url}}">{{$text}}</a>