

// ------------- Start Clock -------------
var clock = 0;
var interval_msec = 1000;

// ready

$(function() {
    // set timer
    clock = setTimeout("UpdateClock()", interval_msec);
});

// UpdateClock
function UpdateClock(){

    // clear timer
    clearTimeout(clock);

    var dt_now = new Date();
    var hh	= dt_now.getHours();
    var mm	= dt_now.getMinutes();
    var ss	= dt_now.getSeconds();

    if(hh < 10){
        hh = "0" + hh;
    }
    if(mm < 10){
        mm = "0" + mm;
    }
    if(ss < 10){
        ss = "0" + ss;
    }
    $("#myclock").html( hh + ":" + mm + ":" + ss);

    // set timer
    clock = setTimeout("UpdateClock()", interval_msec);

}

// ------------------ Stop Clock -----------------


$(document).ready( function () {
    // var total_news = 0;
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/srmsng/public/announcement/news/api/getnotices",
        success: data => {
            data.forEach((datai) => {
                $("#notices").append(`<div class="alert alert-${datai.type}" role="alert">
            <i class="fa fa-info"></i> ${datai.title}</div>`)
            })
        }
    })
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "/srmsng/public/announcement/news/api/getnews/0/5",
        success: (data) => {
            // console.log(data);
            data.forEach((datai) => {
                var badge = "";
                var default_image = "https://increasify.com.au/wp-content/uploads/2016/08/default-image.png";
                if (datai.image !== ""){
                    default_image = datai.image;
                }
                let inputDate = new Date(datai.date);
                let todaysDate = new Date();
                if(inputDate.setHours(0,0,0,0) == todaysDate.setHours(0,0,0,0)) {
                    // Date equals today's date
                    badge = `<span class="badge badge-primary blink" style="margin-left: 0.5em;">New</span>`;
                }
                $("#all-news").append(`
                    <div class="news-item row">
                        <div class="col-sm-2 ">
                            <img class="img-fluid news-image" data-src="holder.js/100px260/" alt="100%x260" src="${default_image}">
                        </div>
                        <div class="col-sm-10">
                            <span class="news-header-wrapper"><h4 class="news-header">${datai.title}</h4><span class="news-date">Posted on ${datai.date} by ${datai.author}</span>${badge}</span>
                            <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${datai.content}</p>
                            <div class="d-flex align-items-end flex-column footee">
                                <a class="mt-auto p-2" href="/srmsng/public/announcement/news/${datai.id}">Read more...</a>
                            </div>
                        </div>
                    </div>`
                )
            })

        }
    }).then(() => {
        var total_news = 0
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "/srmsng/public/announcement/news/api/countnews",
            success: (total) => {
                total_news = total[0].count;
            }
        }).then(() => {
            $('#page-selection').bootpag({
                        total: Math.ceil((total_news/5))
                    }).on("page", function(event, /* page number here */ num){
                        var start_page = (num*5)-5;
                        var stop_page = 5;
                        $("#all-news").empty();
                        $.ajax({
                            type: "GET",
                            dataType: "JSON",
                            url: `/srmsng/public/announcement/news/api/getnews/${start_page}/${stop_page}`,
                            success: (data) => {
                                data.forEach((datai) => {
                                    var default_image = "https://increasify.com.au/wp-content/uploads/2016/08/default-image.png";
                                    if (datai.image !== ""){
                                        default_image = datai.image;
                                    }
                                    $("#all-news").append(`
                                        <div class="news-item row">
                                            <div class="col-sm-2 ">
                                                <img class="img-fluid news-image" data-src="holder.js/100px260/" alt="100%x260" src="${default_image}">
                                            </div>
                                            <div class="col-sm-10">
                                                <span class="news-header-wrapper"><h4 class="news-header">${datai.title}</h4><span class="news-date">Posted on ${datai.date} by ${datai.author}</span></span>
                                                <p>${datai.content}</p>
                                                <div class="d-flex align-items-end flex-column footee">
                                                    <a class="mt-auto p-2" href="/srmsng/public/announcement/news/${datai.id}">Read more...</a>
                                                </div>
                                            </div>
                                        </div>`);
                                })

                            },
                            error: err => {
                                console.log("ERROR: "+ err);
                            }
                        })

                    });

                    $("#page-selection .pagination").each((index, item) => {
                        $(item.children).each((i, it) => {
                            $(it).addClass("page-item");
                            $(it.children).addClass("page-link");

                        })
                    })
                        
                    })
        })
    var listener = new window.keypress.Listener();
    listener.sequence_combo("m a n a g e 1", function() {
        window.location = "/srmsng/public/announcement/news/manage";
    });
                    
    

});
window.addEventListener("beforeunload", function (e) {       
    
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
}); 