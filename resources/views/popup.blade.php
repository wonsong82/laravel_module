@extends ( 'backpack::layout' )


@section('content')
    <div class="row">
        <div class="col-md-12">

            <form
                    action="http://erp.texaking.local/test/popup"
                    method="post"
                    enctype="application/x-www-form-urlencoded"
            >
                {!! csrf_field() !!}
                {!! method_field('POST') !!}

                <input type="text" name="text">

                <input type="submit" value="submit">
            </form>

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