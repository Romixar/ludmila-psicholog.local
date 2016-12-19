/* Будьте внимательны. Мы здесь указали адрес нашего сайта
    и адрес скрипта, обрабатывающего форму.
    Не забудьте изменить их на Ваши.
     */
    var Site = {serverName:location.hostname}; /* адрес сайта */



//
        //console.log(Site.serverName);
//
//    function sendform(){
//        //var msg=$('#addtestmon').serialize();
//        var msg=JSON.stringify($('#addtestmon')).serialize();
//        
//
//        //console.log(msg);
//
//        document.addtestmon.submit.disabled=true; /* блокируем кнопку отправить */
//        document.addtestmon.submit.value="Подождите..."; // меняем надпись на кнопке */
//        $.ajax({
//            type:'POST',
//                    /* адрес php файла, обрабатывающего форму */
//            url:"http://"+Site.serverName+"/ajax.php",
//            data:msg+"&action=sendform",
//            cache:false,
//            success:function(data){
//
//                //$("div#error").html(data);
//
//                console.log($(this).data);
//
//                document.addtestmon.submit.disabled=false;
//
//                document.addtestmon.submit.value="Отправить";
//            },
//            error:function(data){
//
//                console.log(data.addtestmon.name);
//
//            }
//        });
//    }





/*
  Jquery Проверка с помощью jqBootstrapValidation
  Пример взят из jqBootstrapValidation Docs
  */

$(function(){
        
 $("input#name, textarea#message").jqBootstrapValidation({
     
     preventSubmit: true,
//     submitError: function($form, event, errors) {
//      // something to have when submit produces an error ?
//      // Не решил, если мне это нужно еще
//     },
     submitSuccess: function($form, event){
           event.preventDefault(); // запрет отправки
           // беру значения из формы
           var name = $("input#name").val();
//           var nameID = $("input#name-id").val();
           //var phone = $("input#phone").val();
//           var phoneID = $("input#phone-id").val();
           //var email = $("input#email").val();
         
           var message = $("textarea#message").val();
//           var firstName = name; // для сообщения об ошибке отправки

             //console.log(name+' - '+message);

               // Проверка пробелов в имени
//            if (firstName.indexOf(' ') >= 0) {
//                firstName = name.split(' ').slice(0, -1).join(' ');
//            }
            //var obj = {};//здесь будут данные для отправки
//            if(email != ''){
//                obj = {name: name, phone: phone, email: email, message: message};
//
//            }else{
            var obj = {name: name, message: message};
                //console.log('попадаю сюда!');
//            }
            //console.log(obj);
            
            $.ajax({
                         url: "http://" + Site.serverName + "/recall_me.php",
                         type: "POST",
                         data: 'newtestmon=' + JSON.stringify(obj),
                         cache: false,
                         success: function(res){

                             //console.log(JSON.parse(res));// выводим то что пришло от сервера
                             $('div.err').html('');
                             $('div.suc').html('');
                             if(res == ''){
                                 
                                 $('div.err').html('<p>Введите корректный отзыв!</p>');
                                 return false;// запрет отправки в БД на серверt
//                                // если ответ существует то считаем клики 
//                                $('input#sendtestm').click(function(){
//                                 
//                                    console.log('клик!\n');
//                                    
//
//                                })
                                 
                             }
                             
                             
                             
                             
                             var resObj = JSON.parse(res);
                             
                             if(resObj.error){
                                 $('div.err').html('<p>'+ resObj.error +'</p>');
                                 return false;
                             }else $('div.suc').html('<p>Спасибо, ваш отзыв добавлен!</p>');
                             
                             // вставка после всех дочерних элементов (ОТЗЫВОВ)
                             $('div.testimonial-list div.col-md-10').append('<div class="testimonial"><div class="spoiler-head1 heightBlock">"' + resObj.head + '</div><div class="spoiler-body1">' + resObj.body + '"</div><br /><span class="testBlue">' + resObj.name + '</span><br/><span class="testBlue">' + formatDate(new Date()) + '</span><script type="text/javascript">spoiler1()</script></div>');
                             
                             
                             
                             
//                             $(".spoiler-body1").hide();$(".spoiler-head1").click(function(){$(this).next().toggle(600);$(this).toggleClass("heightBlock");})
                             
                             
                             
                             $('#addtestmon').trigger("reset");// очистка формы
                             

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
//         filter: function(){
//                   return $(this).is(":visible");
//         },
        
       });// End jqBootstrapValidation

//$("a[data-toggle=\"tab\"]").click(function(e) {
//    e.preventDefault();
//    $(this).tab("show");
//    });
    
    
    function formatDate(date) {// вывод отформатир-й даты ДД - ММ - ГГГГ
        var dd = date.getDate();
        if (dd < 10) dd = '0' + dd;
        var mm = date.getMonth() + 1;
        if (mm < 10) mm = '0' + mm;
        // вывод двузначного года
        //var yy = date.getFullYear() % 100;
        var yyyy = date.getFullYear();
        //if (yy < 10) yy = '0' + yy;
        return dd + '.' + mm + '.' + yyyy + 'г.';
    }

    
    
});



/*When clicking on Full hide fail/success boxes */ 
//$('#name').focus(function() {
//     $('#success').html('');
//  });

