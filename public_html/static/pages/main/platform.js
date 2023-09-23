jQuery(document).ready(function () {
    var table = $('#datatable').dataTable( {
        ajax: "/main/platform/list"
    } );
});