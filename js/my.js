$(function(){
    
   $('.but').click(function(e){
       
       var ctrl = e.target.value;
       location.href = '/admin/' + ctrl;

   });
    
    
//   $('div.spoiler-trigger').click(function(e){// клик на услуге и переход на ее страницу
//      
//       var id = $(this).data('serviceId');
//       
//       location.href = '/view/' + id;
//       
//       //console.log($(this).data('serviceId'));
//       
//   });
    

    
    
});