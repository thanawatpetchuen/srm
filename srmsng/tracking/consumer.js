
var db = firebase.database();
var ref = db.ref('locations')

function consumeLocations(code){
    ref.child(code).on('value', snapshot => {
        var result = snapshot.val() // Return data
        console.log(result)     
    })
}

consumeLocations(456);
consumeLocations(123);




