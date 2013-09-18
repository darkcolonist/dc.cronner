$(document).ready(function(){
  $(".button.delete").click(function(e){
    var confirmation = confirm("are you sure you wish to delete? this process is irreversible!");
    
    if(!confirmation) return false;
  });
});