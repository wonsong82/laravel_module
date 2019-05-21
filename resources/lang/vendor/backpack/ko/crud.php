<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backpack Crud Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the CRUD interface.
    | You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    // Forms
    'save_action_save_and_new' => '저장 & 새아이템 등록',
    'save_action_save_and_edit' => '저장 & 수정',
    'save_action_save_and_back' => '저장 & 돌아가기',
    'save_action_changed_notification' => '저장후 액션이 수정되었습니다.',

    // Create form
    'add'                 => ':name 등록',
    'back_to_all'         => ':name 리스트로 돌아가기',
    'cancel'              => '취소',
    'add_a_new'           => '새 :name 등록',

    // Edit form
    'edit'                 => '수정',
    'save'                 => '저장',
    'edit_item'            => ':name 수정',

    // Revisions
    'revisions'            => 'Revisions',
    'no_revisions'         => 'No revisions found',
    'created_this'         => 'created this',
    'changed_the'          => 'changed the',
    'restore_this_value'   => 'Restore this value',
    'from'                 => 'from',
    'to'                   => 'to',
    'undo'                 => 'Undo',
    'revision_restored'    => 'Revision successfully restored',
    'guest_user'           => 'Guest User',

    // Translatable models
    'edit_translations' => 'EDIT TRANSLATIONS',
    'language'          => 'Language',

    // CRUD table view
    'all'                       => 'All ',
    'in_the_database'           => 'in the database',
    'list'                      => '목록',
    'actions'                   => '액션',
    'preview'                   => '보기',
    'delete'                    => '삭제',
    'admin'                     => '홈',
    'details_row'               => 'This is the details row. Modify as you please.',
    'details_row_loading_error' => 'There was an error loading the details. Please retry.',

        // Confirmation messages and bubbles
        'delete_confirm'                              => '정말로 삭제하시겠습니까?',
        'delete_confirmation_title'                   => '아이템 삭제',
        'delete_confirmation_message'                 => '아이템이 삭제되었습니다.',
        'delete_confirmation_not_title'               => '삭제 취소',
        'delete_confirmation_not_message'             => "삭제중 에러가 발생하였습니다.",
        'delete_confirmation_not_deleted_title'       => '미삭제',
        'delete_confirmation_not_deleted_message'     => 'Nothing happened. Your item is safe.',

        // Bulk actions
        'bulk_no_entries_selected_title' => 'No entries selected',
        'bulk_no_entries_selected_message' => 'Please select one or more items to perform a bulk action on them.',

        // Bulk confirmation
        'bulk_delete_are_you_sure' => 'Are you sure you want to delete these :number entries?',
        'bulk_delete_sucess_title' => 'Entries deleted',
        'bulk_delete_sucess_message' => ' items have been deleted',
        'bulk_delete_error_title' => 'Delete failed',
        'bulk_delete_error_message' => 'One or more items could not be deleted',

        // Ajax errors
        'ajax_error_title' => 'Error',
        'ajax_error_text'  => 'Error loading page. Please refresh the page.',

        // DataTables translation
        'emptyTable'     => '데이타가 없습니다',
        'info'           => '전체 _TOTAL_개 중 _START_ 에서 _END_',
        'infoEmpty'      => '전체 0개 중 0 에서 0',
        'infoFiltered'   => '(filtered from _MAX_ total entries)',
        'infoPostFix'    => '',
        'thousands'      => ',',
        'lengthMenu'     => '페이지당 _MENU_ 개',
        'loadingRecords' => '로딩중...',
        'processing'     => '처리중...',
        'search'         => '검색: ',
        'zeroRecords'    => '검색 결과가 없습니다',
        'paginate'       => [
            'first'    => '처음',
            'last'     => '마지막',
            'next'     => '다음',
            'previous' => '이전',
        ],
        'aria' => [
            'sortAscending'  => ': activate to sort column ascending',
            'sortDescending' => ': activate to sort column descending',
        ],
        'export' => [
            'copy'              => 'Copy',
            'excel'             => 'Excel',
            'csv'               => 'CSV',
            'pdf'               => 'PDF',
            'print'             => 'Print',
            'column_visibility' => 'Column visibility',
        ],

    // global crud - errors
        'unauthorized_access' => 'Unauthorized access - you do not have the necessary permissions to see this page.',
        'please_fix' => '아래의 항목을 수정하십시요:',

    // global crud - success / error notification bubbles
        'insert_success' => '아이템이 등록되었습니다.',
        'update_success' => '아이템이 수정되었습니다.',
        'delete_success' => '아이템이 삭제되었습니다.',
        'update_unchanged' => '변경된 사항이 없습니다.',

    // CRUD reorder view
        'reorder'                      => 'Reorder',
        'reorder_text'                 => 'Use drag&drop to reorder.',
        'reorder_success_title'        => 'Done',
        'reorder_success_message'      => 'Your order has been saved.',
        'reorder_error_title'          => 'Error',
        'reorder_error_message'        => 'Your order has not been saved.',

    // CRUD yes/no
        'yes' => '예',
        'no' => '아니요',

    // CRUD filters navbar view
        'filters' => 'Filters',
        'toggle_filters' => 'Toggle filters',
        'remove_filters' => 'Remove filters',

    // Fields
        'browse_uploads' => 'Browse uploads',
        'select_all' => 'Select All',
        'select_files' => 'Select files',
        'select_file' => 'Select file',
        'clear' => 'Clear',
        'page_link' => 'Page link',
        'page_link_placeholder' => 'http://example.com/your-desired-page',
        'internal_link' => 'Internal link',
        'internal_link_placeholder' => 'Internal slug. Ex: \'admin/page\' (no quotes) for \':url\'',
        'external_link' => 'External link',
        'choose_file' => 'Choose file',

    //Table field
        'table_cant_add' => 'Cannot add new :entity',
        'table_max_reached' => 'Maximum number of :max reached',

    // File manager
    'file_manager' => 'File Manager',
];
