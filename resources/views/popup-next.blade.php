@extends ( 'backpack::layout' )


@section('content')
    <div class="row">
        <div class="col-md-12">
            You typed {{$text}}.
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary" id="refresh">Close and refresh</button>
            <button class="btn btn-primary" id="result">Close and add result</button>
        </div>
    </div>



@endsection



@push('after_styles')
<style>
    .main-header,
    .main-sidebar,
    .main-footer {
        display: none;
    }
    .content-wrapper {
        margin-left: 0;
    }
    html {
        background: #ecf0f5;
    }
</style>
@endpush


@push('after_scripts')
<script>
    $('#result').click(function(){
        window.parent.closePopup();
        window.parent.showResult('{{$text}}');
    });
    $('#refresh').click(function(){
        window.parent.closePopup();
        window.parent.refreshPage();
    });
</script>
@endpush