@php
$column = $column['name'];
$constant = $entry->$column;
@endphp

<span class="label label-{{$constant->type}}">{{__($constant->key)}}</span>