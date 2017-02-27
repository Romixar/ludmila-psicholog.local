$(function(){
    
   $('.but').click(function(e){
       
       var ctrl = e.target.value;
       location.href = '/admin/'+ ctrl;

   });
    

    
    
});