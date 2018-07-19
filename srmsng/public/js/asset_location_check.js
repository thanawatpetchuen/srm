function get_asset_location_and_customer(sng_code) {
    if (sng_code != '') {
        $.ajax({
            type: "GET",
            data: {sng_code:sng_code},
            url: "/srmsng/public/index.php/api/admin/get_asset_location_and_customer",
            success: function(result){
                result = JSON.parse(result);
                for (var value in result[0]) {
                    $(".form-control[name='" + value + "']").val(result[0][value]);
                }
            },
            error: err => {
                console.log(err);
            }
        });
    }
}

function check_asset_location_code(this_element) {
    sng_code = this_element.value;
    if (sng_code != '') {
        $.ajax({
            type: "GET",
            data: {sng_code:sng_code},
            url: "/srmsng/public/index.php/api/admin/get_asset_location_code",
            success: function(result){
                result = JSON.parse(result);
                if (result[0]['location_code'] != document.getElementsByName('location_code')[0].value) {
                    alert('Assets location doesn\'t match');
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
