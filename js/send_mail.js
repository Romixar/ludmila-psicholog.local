$(function(){
   
    
    var Site = {serverName:location.hostname}; /* адрес сайта */
    $("input.email, textarea#messagemail").jqBootstrapValidation({
     
     preventSubmit: true,

     submitSuccess: function($form, event){
           event.preventDefault(); // запрет отправки
           // беру значения из формы
           var name = $("input#namemail").val();
           var phone = $("input#phone").val();
           var email = $("input#email").val();
           var message = $("textarea#messagemail").val();

           var obj = {name: name, phone: phone, email: email, message: message};
            
           $.ajax({
               url: "http://" + Site.serverName + "/recall_me.php",
               type: "POST",
               data: 'newmail=' + JSON.stringify(obj),
               cache: false,
               success: function(res){
               //console.log(JSON.parse(res));// выводим то что пришло от сервера
               //$('div.err').html('');
               //$('div.suc').html('');
                    if(res != ''){
                                 
                        alert(res);
                                 
                    }
                             
                    $('#contactForm').trigger("reset");// очистка формы
                             

            //                if(message != ''){
            //                    // Success message
            //                    $('#success1').html("<div class='alert alert-success'>");
            //                    $('#success1 > .alert-success').append("<strong>Спасибо! Ваш запрос принят, ожидайте звонка!</strong>");
            //                    $('#success1 > .alert-success').append('</div>');
            //                    //clear all fields
            //                    $('form#contactForm1').trigger("reset");
            //                }else{
            //                    // Success message
            //                    $('#success').html("<div class='alert alert-success'>");
            //                    $('#success > .alert-success').append("<strong>Спасибо! Ваш запрос принят, ожидайте звонка!</strong>");
            //                    $('#success > .alert-success').append('</div>');
            //                    //clear all fields
            //                    $('form#contactForm').trigger("reset");
            //                    //закрытие модал окна после отправки
            //                    setTimeout(function() {
            //                                    $("#myModal").modal('hide');
            //                               }, 4000);       
            //                }

                        },
                 error: function(){

                     console.log('ошибка');


            //         if(message != ''){
            //             
            //             // Fail message
            //             $('#success1').html("<div class='alert alert-danger'>");
            ////             $('#success1 > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;").append( "</button>");
            //             $('#success1 > .alert-danger').append("<strong>Извините "+firstName+", возникла ошибка отправки.</strong><br/>Вы можете написать напрямую на адрес <a href='duby.ludmila@yandex.ru?Subject=Перезвоните мне'>duby.ludmila@yandex.ru</a>");
            //             $('#success1 > .alert-danger').append('</div>');
            //             //clear all fields
            //             $('#contactForm1').trigger("reset");
            //             
            //         }else{
            //             
            //             // Fail message
            //             $('#success').html("<div class='alert alert-danger'>");
            //             $('#success > .alert-danger').append("<strong>Извините "+firstName+", возникла ошибка отправки.</strong><br/>Вы можете написать напрямую на адрес <a href='duby.ludmila@yandex.ru?Subject=Перезвоните мне'>duby.ludmila@yandex.ru</a>");
            //             $('#success > .alert-danger').append('</div>');
            //             //clear all fields
            //             $('#contactForm').trigger("reset");
            //             
            //         }

                 }

            })





         },
        
       });// End jqBootstrapValidation

    
    
});