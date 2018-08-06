$(document).ready(() => {
    var database = firebase.database();
    var ref = database.ref("locations");

    // -----------------Produce data
    // database.ref("geolocation").child('555').set({
    //     fse_code : "110",
    //     lat : "2",
    //     lon : "3",
    //     status : "r"
    // });

    // database.ref("geolocation").push({
    //     fse_code : "110",
    //     lat : "2",
    //     lon : "3",
    //     status : "r"
    // });
    // database.ref("geolocation").child('666').update({
    //     fse_code : "110",
    //     lat : "2",
    //     lon : "3",
    //     status : "r"
    // });

    // database.ref("geolocation").transaction((current) => {
    //     console.log(current);
    // });

    // --------------Consume data
    // read realtime

    // ref.child("555").once("value", (snapshot) => {
    //     var res = snapshot.val();
    //     console.log(snapshot.val());
    //     // return snapshot.json();
    //     document.getElementById("id").innerHTML = res.fse_code;
    //     document.getElementById("lat").innerHTML = res.lat;
    //     document.getElementById("lon").innerHTML = res.lon;
    //     document.getElementById("time").innerHTML = res.status;
    // });

    // ref.child("555").child("fse_code").on("value", (snapshot) => {
    //     var res = snapshot.val();
    //     console.log(snapshot.val());
    //     // return snapshot.json();
    //     document.getElementById("fse").innerHTML = res;
    // })
    ref.on("value", snapshot => {
        var res = snapshot.val();
        var id = "123"
        var first = res[id];
        console.log(first);
        document.getElementById("id").innerHTML = id;
        document.getElementById("lat").innerHTML = first.latitude;
        document.getElementById("lon").innerHTML = first.longitude;
        document.getElementById("time").innerHTML = first.time;
    })
})





