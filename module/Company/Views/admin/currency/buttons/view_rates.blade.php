@php
$route = route('business::crud.currency-pair.index');
$query = http_build_query([
    'base_currency' => $entry->code
]);
@endphp
<a class="btn btn-xs btn-default" href="{{ $route }}?{{$query}}">View Rates</a>