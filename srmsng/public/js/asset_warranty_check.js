function get_asset_warranty(sng_code) {
    if (sng_code != '') {
        $.ajax({
            type: "GET",
            data: {sng_code:sng_code},
            url: "/srmsng/public/index.php/api/admin/get_asset_warranty",
            success: function(result){
                result = JSON.parse(result);
                for (var value in result[0]) {
                    $(".form-control[name='warranty_" + value + "']").val(result[0][value]);
                }
            },
            error: err => {
                console.log(err);
            }
        });
    }
}

function check_asset_warranty(this_element) {
    sng_code = this_element.value;
    if (sng_code != '') {
        $.ajax({
            type: "GET",
            data: {sng_code:sng_code},
            url: "/srmsng/public/index.php/api/admin/get_asset_warranty",
            success: function(result){
                //start_date, end_date, year_count, times_per_year
                result = JSON.parse(result);
                if (result[0]['start_date'] != document.getElementsByName('warranty_start_date')[0].value
                    || result[0]['end_date'] != document.getElementsByName('warranty_end_date')[0].value
                    || result[0]['year_count'] != document.getElementsByName('warranty_year_count')[0].value
                    || result[0]['times_per_year'] != document.getElementsByName('warranty_times_per_year')[0].value) {
                    alert('Assets warranty doesn\'t match');
                    this_element.setAttribute("class", "form-control is-invalid");
                } else {
                    this_element.setAttribute("class", "form-control");
                }
            },
            error: err => {
                console.log(err);
            }
        });
    }
}
