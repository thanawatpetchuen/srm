function onclose(){
    let remember = "<?php echo $_SESSION['remember']?>";
    
    if(remember == "off"){
        console.log("UNDLOAD");
        $.ajax({
            type: "POST",
            url: '/srmsng/public/index.php/logout',
            // async: false,/         
           
            success: data => {
                console.log(data);
            },
            error: err => {
                console.log(err);
            }
    
        });
        return;
    }

}
            