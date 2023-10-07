const URL = '/menu/device/';

const STRUCTURE = {
    store_id : {type:'text'},
    device_uuid :{type:'text'},
    status: {type:'checkbox',default: true}
}
jQuery(document).ready(function () {
    init();
    //EVENT
    $('#add').on('click', function (e) {
        console.log($('#modalAdd'));
        $('#modalAdd').modal('show');
        let form = $('#add-form');
        clearForm(STRUCTURE,form);
    });

    $('#btn-add').on('click', function (e) {
        let form = $('#add-form');
        let data = getDataForm(STRUCTURE,form);

        $.ajax({
            method: 'POST',
            url:  URL + 'update',
            dataType: 'json',
            data: data,
            success: function (response) {
                console.log(response)
                if (response.err === 1) {

                } else {
                    getList();
                }
                $('#modalAdd').modal('hide');
            },
            error: function (error) {
                console.log(error);
            }
        });


    });
    $(document).on('click', '.edit', function () {
        var formData = {
            id: $(this).data('id')
        };
        $.ajax({
            method  : 'POST',
            url     :  URL + "get",
            dataType: 'json',
            data    : formData,
            success : function (response) {
                if (response.err === 1) {

                } else {

                    //clearForm(STRUCTURE,form);
                    $('#modalAdd').modal('show');
                    let form = $('#add-form');
                    var data = response.data;
                    updateForm(STRUCTURE,form,data);

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });




});
function init(){
    getList();
}

function getList(){
    $.ajax({
        method: 'GET',
        url:  URL + 'list',
        dataType: 'json',
        success: function (response) {
            if (response && response.data){
                $('#table-data').empty();
                for (const rowData of response.data)  {
                    let cols = "";
                    for (const col of rowData) {
                        cols += `<td >${col}</td>`;
                    }
                    $('#table-data').append(`<tr>${cols}</tr>`);
                }
            }
        },
        error: function (error) {
            console.log(error);
        }
    });

}
