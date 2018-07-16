$(document).ready(function(){
    var selecter = document.getElementById("customer_select");
    var op1 = document.createElement("option");
    var op1_text = document.createTextNode("David");
    op1.appendChild(op1_text);
    var op2 = document.createElement("option");
    var op2_text = document.createTextNode("Somchai");
    op2.appendChild(op2_text);
    selecter.appendChild(op1);
    selecter.appendChild(op2);

});