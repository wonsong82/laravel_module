@extends ( 'backpack::layout' )

@section('content')
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary" data-toggle="modal" data-target="#popup">Popup</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <textarea id="result" cols="30" rows="10"></textarea>
        </div>
    </div>






    <div class="modal fade" id="popup">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>

                <div class="modal-body no-padding">
                    <iframe id="popup-content" frameborder="0" width="100%" height="100%"></iframe>
                </div>

                <div class="modal-footer">
                    btn
                </div>


            </div>
        </div>
    </div>

@endsection

@push('after_styles')

@endpush

@push('after_scripts')
<script>
    window.closePopup = function(){
        $('#popup').modal('hide');
    };

    window.refreshPage = function(){
        window.location.href = window.location.href;
    };

    window.showResult = function(result){
        $('#result').val(result);
    };


    $(function(){

        $('#popup').on('show.bs.modal', function(){
            var $content = $(this).find('#popup-content');

            $content.attr('src', 'http://erp.texaking.local/test/popup');
        });


        $('#ajax').click(function(){
            $.get('http://erp.texaking.local/test/ajax');
        });

    });
</script>
@endpush

