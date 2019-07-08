//e.g usage
//<input type="text" id="amount" onkeyup="format_as_number(this.id)">
// adds commer separator
function format_as_number(elementId) {
    var x = document.getElementById(elementId);           
    var num = x.value
    .replace(/\D/g, "")
    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
   x.value = num;    
}