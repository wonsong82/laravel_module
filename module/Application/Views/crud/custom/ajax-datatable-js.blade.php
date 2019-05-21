<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js"></script>
<script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
<script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
<script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>

<script>
    var crud = {
        exportButtons: JSON.parse('{!! json_encode($crud->export_buttons) !!}'),
        functionsToRunOnDataTablesDrawEvent: [],
        addFunctionToDataTablesDrawEventQueue: function (functionName) {
            if (this.functionsToRunOnDataTablesDrawEvent.indexOf(functionName) == -1) {
                this.functionsToRunOnDataTablesDrawEvent.push(functionName);
            }
        },
        responsiveToggle: function(dt) {
            $(dt.table().header()).find('th').toggleClass('all');
            dt.responsive.rebuild();
            dt.responsive.recalc();
        },
        executeFunctionByName: function(str, args) {
            var arr = str.split('.');
            var fn = window[ arr[0] ];

            for (var i = 1; i < arr.length; i++)
            { fn = fn[ arr[i] ]; }
            fn.apply(window, args);
        },
        dataTableConfiguration: {

            @if ($crud->getResponsiveTable())
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal( {
                        header: function ( row ) {
                            // show the content of the first column
                            // as the modal header
                            var data = row.data();
                            return data[0];
                        }
                    } ),
                    renderer: function ( api, rowIdx, columns ) {
                        var data = $.map( columns, function ( col, i ) {
                            var allColumnHeaders = $("#crudTable thead>tr>th");

                            if ($(allColumnHeaders[col.columnIndex]).attr('data-visible-in-modal') == 'false') {
                                return '';
                            }

                            return '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td style="vertical-align:top;"><strong>'+col.title.trim()+':'+'<strong></td> '+
                                '<td style="padding-left:10px;padding-bottom:10px;">'+col.data+'</td>'+
                                '</tr>';
                        } ).join('');

                        return data ?
                            $('<table class="table table-striped table-condensed m-b-0">').append( data ) :
                            false;
                    },
                }
            },
            @else
            responsive: false,
            scrollX: true,
            @endif

            autoWidth: false,
            pageLength: {{ $crud->getDefaultPageLength() }},
            lengthMenu: @json($crud->getPageLengthMenu()),
            /* Disable initial sort */
            aaSorting: [],
            language: {
                "emptyTable":     "{{ trans('backpack::crud.emptyTable') }}",
                "info":           "{{ trans('backpack::crud.info') }}",
                "infoEmpty":      "{{ trans('backpack::crud.infoEmpty') }}",
                "infoFiltered":   "{{ trans('backpack::crud.infoFiltered') }}",
                "infoPostFix":    "{{ trans('backpack::crud.infoPostFix') }}",
                "thousands":      "{{ trans('backpack::crud.thousands') }}",
                "lengthMenu":     "{{ trans('backpack::crud.lengthMenu') }}",
                "loadingRecords": "{{ trans('backpack::crud.loadingRecords') }}",
                "processing":     "<img src='{{ asset('vendor/backpack/crud/img/ajax-loader.gif') }}' alt='{{ trans('backpack::crud.processing') }}'>",
                "search":         "{{ trans('backpack::crud.search') }}",
                "zeroRecords":    "{{ trans('backpack::crud.zeroRecords') }}",
                "paginate": {
                    "first":      "{{ trans('backpack::crud.paginate.first') }}",
                    "last":       "{{ trans('backpack::crud.paginate.last') }}",
                    "next":       "<span class='hidden-xs hidden-sm'>{{ trans('backpack::crud.paginate.next') }}</span><span class='hidden-md hidden-lg'>></span>",
                    "previous":   "<span class='hidden-xs hidden-sm'>{{ trans('backpack::crud.paginate.previous') }}</span><span class='hidden-md hidden-lg'><</span>"
                },
                "aria": {
                    "sortAscending":  "{{ trans('backpack::crud.aria.sortAscending') }}",
                    "sortDescending": "{{ trans('backpack::crud.aria.sortDescending') }}"
                },
                "buttons": {
                    "copy":   "{{ trans('backpack::crud.export.copy') }}",
                    "excel":  "{{ trans('backpack::crud.export.excel') }}",
                    "csv":    "{{ trans('backpack::crud.export.csv') }}",
                    "pdf":    "{{ trans('backpack::crud.export.pdf') }}",
                    "print":  "{{ trans('backpack::crud.export.print') }}",
                    "colvis": "{{ trans('backpack::crud.export.column_visibility') }}"
                },
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: '',
                type: 'POST'
            },
            dom:
            "<'row'<'col-sm-6 hidden-xs'l><'col-sm-6 hidden-print'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-2'B><'col-sm-5 hidden-print'p>>",

            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },


        }
    }
</script>
@include('crud::inc.export_buttons')


<script>
    $(function(){
        $('.ajax-data-table').each(function(){
            var _this = this,
                ajaxUrl = $(_this).attr('data-ajax');

            $.get(ajaxUrl, function(data){
                $(_this).html(data);

                var table = $('.crudAjaxTable', _this),
                    searchUrl = $(_this).attr('data-search'),
                    defaultSearchUrl = table.attr('data-search-url'),
                    leftFreeze = parseInt(table.attr('data-left-freeze')),
                    rightFreeze = parseInt(table.attr('data-right-freeze'));

                if(!searchUrl) {
                    searchUrl = defaultSearchUrl;
                }

                crud.dataTableConfiguration.ajax.url = searchUrl;
                crud.dataTableConfiguration.fixedColumns = {
                    leftColumns: leftFreeze,
                    rightColumns: rightFreeze
                }

                var dataTable = table.DataTable(crud.dataTableConfiguration);

                // override ajax error message
                $.fn.dataTable.ext.errMode = 'none';
                table.on('error.dt', function(e, settings, techNote, message) {
                    new PNotify({
                        type: "error",
                        title: "{{ trans('backpack::crud.ajax_error_title') }}",
                        text: "{{ trans('backpack::crud.ajax_error_text') }}"
                    });
                });

                // make sure AJAX requests include XSRF token
                $.ajaxPrefilter(function(options, originalOptions, xhr) {
                    var token = $('meta[name="csrf_token"]').attr('content');

                    if (token) {
                        return xhr.setRequestHeader('X-XSRF-TOKEN', token);
                    }
                });

                // on DataTable draw event run all functions in the queue
                // (eg. delete and details_row buttons add functions to this queue)
                table.on( 'draw.dt',   function () {
                    crud.functionsToRunOnDataTablesDrawEvent.forEach(function(functionName) {
                        crud.executeFunctionByName(functionName);
                    });
                } ).dataTable();

                // when datatables-colvis (column visibility) is toggled
                // rebuild the datatable using the datatable-responsive plugin
                table.on( 'column-visibility.dt',   function (event) {
                    dataTable.responsive.rebuild();
                } ).dataTable();

                @if ($crud->getResponsiveTable())
                // when columns are hidden by reponsive plugin,
                // the table should have the has-hidden-columns class
                dataTable.on( 'responsive-resize', function ( e, datatable, columns ) {
                    if (dataTable.responsive.hasHidden()) {
                        table.removeClass('has-hidden-columns').addClass('has-hidden-columns');
                    } else {
                        table.removeClass('has-hidden-columns');
                    }
                } );
                @else
                // make sure the column headings have the same width as the actual columns
                // after the user manually resizes the window
                var resizeTimer;
                function resizeCrudTableColumnWidths() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function() {
                        // Run code here, resizing has "stopped"
                        dataTable.columns.adjust();
                    }, 250);
                }
                $(window).on('resize', function(e) {
                    resizeCrudTableColumnWidths();
                });
                $(document).on('expanded.pushMenu', function(e) {
                    resizeCrudTableColumnWidths();
                });
                $(document).on('collapsed.pushMenu', function(e) {
                    resizeCrudTableColumnWidths();
                });
                @endif

            });
        });
    });
</script>