$(document).ready(function(){
    // let doc = new DOMParser().parseFromString('<div><b>Hello!\
    // </b></div>', 'text/html');
    // var tt = document.getElementById('test');
    // let div = doc.body.firstChild;

    // let divs = doc.body.querySelectorAll('div');
    // // console.log(div);
    // tt.appendChild(div);
});

$("#testSubmit").on("click", () => {
    var tt = document.getElementById('test');
    let location_div = new DOMParser().parseFromString(
       '<legend>Location</legend>\
       <div class="form-group">\
           <label>Site Name</label>\
           <input type="text" class="form-control" name="sitename" placeholder="Site Name"/>\
       </div>\
       <div class="form-group">\
           <label>Location Code</label>\
           <input type="text" class="form-control" name="location_code" placeholder="Location Code"/>\
       </div>\
       <div class="row">\
           <div class="col">\
               <div class="form-group">\
                   <label>House No.</label>\
                   <input type="text" class="form-control" name="house_no" placeholder="House No."/>\
               </div>\
           </div>\
           <div class="col">\
               <div class="form-group">\
                   <label>Village No.</label>\
                   <input type="text" class="form-control" name="village_no" placeholder="Village No."/>\
               </div>\
           </div>\
       </div>\
       <div class="form-group">\
           <label>Road</label>\
           <input type="text" class="form-control" name="road" placeholder="Road"/>\
       </div>\
       <div class="row">\
           <div class="col">\
               <div class="form-group">\
                   <label>Sub-district</label>\
                   <input type="text" class="form-control" name="sub_district" placeholder="Sub-district"/>\
               </div>\
           </div>\
           <div class="col">\
               <div class="form-group">\
                   <label>District</label>\
                   <input type="text" class="form-control" name="district" placeholder="District"/>\
               </div>\
           </div>\
       </div>\
       <div class="row">\
           <div class="col">\
               <div class="form-group">\
                   <label>Province</label>\
                   <input type="text" class="form-control" name="province" placeholder="Province"/>\
               </div>\
           </div>\
           <div class="col">\
               <div class="form-group">\
                   <label>Postal Code</label>\
                   <input type="text" class="form-control" name="postal_code" placeholder="Postal Code"/>\
               </div>\
           </div>\
       </div>\
       <div class="row">\
           <div class="col">\
               <div class="form-group">\
                   <label>Region</label>\
                   <input type="text" class="form-control" name="region" placeholder="Region"/>\
               </div>\
           </div>\
           <div class="col">\
               <div class="form-group">\
                   <label>Country</label>\
                   <input type="text" class="form-control" name="country" placeholder="Country"/>\
               </div>\
           </div>\
       </div>'
        
       , 'text/html');
    let ff = location_div.body;
    tt.appendChild(ff);
    console.log(ff);
})