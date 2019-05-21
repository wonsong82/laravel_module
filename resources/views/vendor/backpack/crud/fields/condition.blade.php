@if(isset($field['conditions']))

    @push('crud_fields_scripts')

        <script>
            @foreach($field['conditions'] as $condition)
            @php
            $src = $condition['condition'][0];
            $compare = $condition['condition'][1];
            $value = $condition['condition'][2];
            $actions = json_encode($condition['actions']);
            @endphp

            $(function(){
                var element = $('[name="{{ $src }}"]'),
                    compare = '{{ $compare }}',
                    value = '{{ $value }}',
                    actions = JSON.parse('{!! $actions !!}');
                    if(!element.length){
                        element = $('[name="{{ $src }}[]"]');
                    }


                if(element.is('select') || element.is('input[type="text"]')){
                    element.change(handle);
                    handle();
                }
                // Todo : if(element.is('othertype'){}



                function handle(){
                    if(compare == '='){
                        if(element.val() == value){
                            $.each(actions, function(k,v){
                                var formgroup = $('[name="' + k + '"]').closest('.form-group');
                                if(!formgroup.length){
                                    formgroup = $('[name="' + k + '[]"]').closest('.form-group');
                                }
                                switch(v){
                                    case 'show':
                                        formgroup.css('display','initial');
                                        break;
                                    case 'hide':
                                        formgroup.css('display','none');
                                        break;
                                }
                            });
                        }
                    }
                    // Todo : if(compare == 'other condition')
                }


            });
            @endforeach
        </script>

    @endpush
@endif
