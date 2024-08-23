// var customurl = SITE_URL;
$(document).ready(function(){
    
    setTimeout(function(){ $('.alert').fadeOut(3000); }, 3000);
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','.icon_loader',function(){
        var $this = $(this);
        var html = $this.html();

        var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i>';
        $(this).html(loadingText);
        $(this).prop("disabled", true);

        setTimeout(function(){ 
            $('.icon_loader').html(html);
            $('.icon_loader').prop("disabled", false);
        }, 5000);
    });

    $('#cms_page_table_en').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 5, 6] },
            { "orderable": true, "targets": [1, 2, 3, 4] } ]
    });

    $('#cms_page_table_german').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 5, 6] },
            { "orderable": true, "targets": [1, 2, 3, 4] } ]
    });

    $('#faq_table_en').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 4, 5] },
            { "orderable": true, "targets": [1, 2, 3] } ]
    });

    $('#faq_table_german').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
       "columnDefs": [ { "orderable": false, "targets": [0, 4, 5] },
            { "orderable": true, "targets": [1, 2, 3] } ]
    });

    $('#faq_category_table_en').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 3, 4] },
            { "orderable": true, "targets": [1,2] } ]
    });

    $('#faq_category_table_german').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 3, 4] },
            { "orderable": true, "targets": [1,2] } ]
    });
    
    $('#app_user_list_table_en').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 1, 6] },
            { "orderable": true, "targets": [2, 3, 4, 5] } ]
    });

    $('#app_user_list_table_german').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 1, 6] },
            { "orderable": true, "targets": [2, 3, 4, 5] } ]
    });
    
    $('#game_table').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 1, 5, 6] },
            { "orderable": true, "targets": [2, 3, 4] } ]
    });
    $('#game_table_de').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 1, 5, 6] },
            { "orderable": true, "targets": [2, 3, 4] } ]
    });

    $('#game_item_table').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0, 1, 5, 6] },
            { "orderable": true, "targets": [2, 3, 4] } ]
    });
    $('#game_item_table_de').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "columnDefs": [ { "orderable": false, "targets": [0, 1, 5, 6] },
            { "orderable": true, "targets": [2, 3, 4] } ]
    });


    $('#premium_game_table').dataTable({
        "bDestroy": false, "lengthChange": false, "bFilter": false, "pageLength": 10,
        "bPaginate": false, "paging": false, "bInfo": false, "stateSave": false,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2] } ]
    });
    $('#medium_game_table').dataTable({
        "bDestroy": false, "lengthChange": false, "bFilter": false, "pageLength": 10,
        "bPaginate": false, "paging": false, "bInfo": false, "stateSave": false,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2] } ]
    });
    $('#free_game_table').dataTable({
        "bDestroy": false, "lengthChange": false, "bFilter": false, "pageLength": 10,
        "bPaginate": false, "paging": false, "bInfo": false, "stateSave": false,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2] } ]
    });

    $('#premium_game_table_de').dataTable({
        "bDestroy": false, "lengthChange": false, "bFilter": false, "pageLength": 10,
        "bPaginate": false, "paging": false, "bInfo": false, "stateSave": false,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2] } ]
    });
    $('#medium_game_table_de').dataTable({
        "bDestroy": false, "lengthChange": false, "bFilter": false, "pageLength": 10,
        "bPaginate": false, "paging": false, "bInfo": false, "stateSave": false,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2] } ]
    });
    $('#free_game_table_de').dataTable({
        "bDestroy": false, "lengthChange": false, "bFilter": false, "pageLength": 10,
        "bPaginate": false, "paging": false, "bInfo": false, "stateSave": false,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
       "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2] } ]
    });

    $('#rate_table_de').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
       "columnDefs": [ { "orderable": false, "targets": [0, 5] },
            { "orderable": true, "targets": [1, 2, 3, 4] } ]
    });

    $('#rate_table').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
       "columnDefs": [ { "orderable": false, "targets": [0, 5] },
            { "orderable": true, "targets": [1, 2, 3, 4] } ]
    });

    $('#notification_table_de').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
       "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2, 3] } ]
    });

    $('#notification_table_en').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
       "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2, 3] } ]
    });

    $('#reward_history_table').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2, 3, 4] } ]
    });

    $('#reward_history_table_de').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true, "export":true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2, 3, 4] } ]
    });

    $('#user_data_code_de').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true, "export":true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2, 3, 4] } ]
    });

    $('#user_data_code').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true, "export":true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0] },
            { "orderable": true, "targets": [1, 2, 3, 4] } ]
    });

    $('#promocode_table').dataTable({ 
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true, "export":true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0,4] },
            { "orderable": true, "targets": [1, 2, 3] } ]
    });

    $('#promocode_table_de').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true, "export":true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0,4] },
            { "orderable": true, "targets": [1, 2, 3] } ]
    });


    $('#tag_table_en').dataTable({ 
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true, "export":true,
        "language": { searchPlaceholder: 'Search'},
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0,3] },
            { "orderable": true, "targets": [1, 2] } ]
    });

    $('#tag_table_german').dataTable({
        "bDestroy": true, "lengthChange": true, "bFilter": true, "pageLength": 10,
        "bPaginate": true, "paging": true, "bInfo": true, "stateSave": true, "export":true,
        "language": { searchPlaceholder: 'Search'},
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        },
        "order": [],
        "columnDefs": [ { "orderable": false, "targets": [0,3] },
            { "orderable": true, "targets": [1, 2] } ]
    });

    $("#like_history_table").DataTable({
      "pageLength": 50,"responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["csv", "excel"]
    }).buttons().container().appendTo('#like_history_table_wrapper .col-md-6:eq(0)');

});

function updateStatus(url,user_id,status,token){
    $.ajax({
        url: url,
        type: 'POST',
        data: { user_id:user_id, status:status, _token:token }
    });
}