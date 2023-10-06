const URL = '/menu/store/';

const STRUCTURE = {
    id: {type:'text'},
    name : {type:'text'},
    phone : {type:'text'},
    address : {type:'text'},
    ward_id : {type:'selected'},
    district_id : {type:'selected'},
    city_id : {type:'selected'},
    store_type: {type:'selected'},
    wifi_pass : {type:'text'},
    email: {type:'text'},
    status: {type:'checkbox',default: true}
};
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

    //event
    $(document).on('change', '#city', function () {
        var formData = {
            id: $(this).val()
        };
        $.ajax({
            method  : 'POST',
            url     :  URL + "get-district",
            dataType: 'json',
            data    : formData,
            success : function (response) {
                if (response.err === 1) {

                } else {
                    $('#district').empty();
                    let html = '';
                    html += `<option value="0"> Please select district </option>`
                    for (const d of response.data) {
                        html += `<option value="${d.id}"> ${d.name} </option>`
                    }
                    $('#district').html(html);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    $(document).on('change', '#district', function () {
        var formData = {
            id: $(this).val()
        };
        $.ajax({
            method  : 'POST',
            url     :  URL + "get-ward",
            dataType: 'json',
            data    : formData,
            success : function (response) {
                if (response.err === 1) {

                } else {
                    $('#ward').empty();
                    let html = '';
                    html += `<option value="0"> Please select ward </option>`
                    for (const d of response.data) {
                        html += `<option value="${d.id}"> ${d.name} </option>`
                    }
                    $('#ward').html(html);

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    ///MENU

    $(document).on('click', '.menu', function () {
        $('#modalMenu').modal('show');
        return;

    });






});
function init(){
    // Select2
    if (jQuery().select2) {
        $(".select2").select2();
    }
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
