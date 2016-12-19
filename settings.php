<?php
require 'db.php'; // 
//require_once 'getFromDB.php';

abstract class HTMLPage extends RedBean_SimpleModel{// подключение класса RedBeanPHP

	//protected $title="";
	public $title="";
    public $info;//будет инфа для вывода на разные страницы

    function __construct($title){
		$this -> title = $title;
        //$this -> info = new GetInfoFromDB();//класс для вывода инфы на разные страницы
	}

	function BeginHTML(){
?>       
<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   	<title><?= $this -> title ?></title>
	<meta name="keywords" content="семейный, психолог, Пьянкова, Людмила" />
	<meta name="description" content="Преодоление проблем в сфере детско-родительских, юношеских, семейных и любовных отношений, одиночества, социофобий, компьютерной зависимости" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="yandex-verification" content="2020ad78441e4696" />
	<link rel="icon" href="http://psicholog-ludmila.ru/favicon.ico" type="image/x-icon">
 	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700,800' rel='stylesheet' type='text/css'/> 
   	<link rel="stylesheet" href="css/font-awesome.min.css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/> 
   	<link rel="stylesheet" href="css/templatemo_misc.css"/>
	<link rel="stylesheet" href="css/templatemo_style.css"/>
    <link rel="stylesheet" href="css/style.css"/>
   	<!-- JavaScripts -->   
    <script src="js/jquery-1.11.1.min.js"></script>  <!-- lightbox -->
	<script src="js/templatemo_custom.js"></script> <!-- lightbox -->
    <script src="js/jquery.lightbox.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="js/jqbootstrapvalidation.js"></script>
    <script src="js/recall_me.js"></script>
    <script src="js/send_mail.js"></script>
    <!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
    <body>
         <?php
	 }
	
	function EndHTML(){
        ?>
                <!-- footer start -->
<!--    EndHTML start   -->
        <div class="clear"></div>
            
		<div class="container">
        	<div class="row">
            	<div class="templatemo_footer">
                
<!--            		<div class="col-md-6">Copyright &copy; 2084 Company Name</div>-->
            		<div class="col-md-6">САЙТ О ПСИХОЛОГИИ, ПСИХОЛОГАХ И ПСИХОТЕРАПИИ</div>
                    <div class="col-md-6">
                        
                    	<div class="social"><a href="###" target="_blank"><img src="images/templatemo_fb.jpg" alt="Людмила Пьянкова в facebook" title="Людмила Пьянкова в facebook"></a></div>
                        <div class="social"><a href="https://www.youtube.com/channel/UCCUILG19M3aIbdZYr-Yi7tQ" target="_blank"><img src="images/488897youtube.png" alt="Людмила Пьянкова на youtube" title="Людмила Пьянкова на youtube"></a></div>
                        <div class="social"><a href="skype:duby.ludmila?call" target="_blank"><img src="images/skype-icon-26.png" alt="позвонить психологу по skype" title="Людмила Пьянкова в skype"></a></div>

                    </div>
                    <div class="clear"></div>
                    
                </div>
            </div>
		</div>
        <!-- footer end -->
      <script type="text/javascript">
          // перезагрузка при клике на главную
          $('a.templatemo_home').click(function(){
              location.href = location.href;
          })
        spoiler();// Спойлер (Открывает/Закрывает полный текст) на ОТЗЫВАХ
          
        function spoiler(){
            $('.spoiler-body').hide();
            $('.spoiler-head').click(function(){
                $(this).next().toggle(600);
                $(this).toggleClass('heightBlock');
            })
        }
        function spoiler1(){
            $('.spoiler-body1').hide();
            $('.spoiler-head1').click(function(){
                $(this).next().toggle(600);
                $(this).toggleClass('heightBlock');
            })
        }
          
          //делегир-е клика с главной на стр услуг
          $('#but1').click(function(){
               $('#but11').click();
            });
          $('#but2').click(function(){
               $('#but22').click();
            });
          $('#but4').click(function(){
               $('#but44').click();
            });
          
          //запуск слайдера
          htmSlider();
          //при фокусе полей скрытие placeholder
          var plcHol;//здесь будут храниться placeholder каждого поля
          $("input").focusin(function(){
                plcHol = $(this)[0].placeholder;
                $(this).attr('placeholder','');
            }).focusout(function(){
                $(this).attr('placeholder',plcHol);
            });
          $("textarea").focusin(function(){
                plcHol = $(this)[0].placeholder;
                $(this).attr('placeholder','');
            }).focusout(function(){
                $(this).attr('placeholder',plcHol);
            });
          
          //переключение спойлеров
          $(".spoiler-trigger").click(function(){
		       $(this).parent().next().collapse('toggle');
	        });
          
          // подстановка видео при клике на превью
          // data-start="32" data-end="60" - время начала и окончания
          // &autoplay=1 фвтоначало
          //var IMG = document.querySelectorAll('#videoIMG img'),
          var IMG = document.querySelectorAll('.slide-wrap img'),
          IFRAME = document.querySelector('#video12 iframe');
          for(var i = 0; i < IMG.length; i++){
              IMG[i].onclick = function(){
                  var idIMG = this.src.replace(/http...img.youtube.com.vi.([\s\S]*?).1.jpg/g, '$1');
                  IFRAME.src = 'http://www.youtube.com/embed/' + idIMG + '?rel=0&autoplay=1';
                  if(this.dataset.end)
                      IFRAME.src = IFRAME.src.replace(/([\s\S]*)/g, '$1&end=' + this.dataset.end);
                  if(this.dataset.start)
                      IFRAME.src = IFRAME.src.replace(/([\s\S]*)/g, '$1&start=' + this.dataset.start);
                  this.style.backgroundColor='#555';
                  // название и автора видео в блок справа
                  var title = this.nextElementSibling.textContent;
                  var autor = this.nextElementSibling.nextElementSibling.textContent;
                  title = document.createTextNode(title);
                  autor = document.createTextNode(autor);
                  var divTitle = document.getElementById('botheader');
                  var divAutor = document.getElementById('botsubheader');  
                  divTitle.textContent='';
                  divAutor.textContent='';
                  divTitle.appendChild(title);
                  divAutor.appendChild(autor);   
              }
          }
      </script>
  </body>
</html>
        
        <?php
	}

	function menuHeader($phone){
        ?>
          <!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter41047424 = new Ya.Metrika({
                    id:41047424,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/41047424" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
  <!-- menu start -->
        <div class="menu">
            
        <div class="container">
            
    	<div class="row navtext">
			<div class="templatemo_topwrapper shadow">
            	<div itemscope itemtype="http://schema.org/LocalBusiness" class="col-sm-4">
                	<div itemprop="legalName" class="templatemo_webtitle">Ваш психолог
                 	<span class="visibility-xs hidden-sm">Людмила Пьянкова</span>
                 	<span class="visibility-xs hidden-sm"><?= $phone ?></span>
                	</div>
                	<span itemscope itemtype="http://schema.org/Person">
                	<p class="hidden-xs visibility-sm" itemprop="name">Людмила Пьянкова </p>
                	<p class="hidden-xs visibility-sm" itemprop="telephone"><?= $phone ?></p>
                	</span>
                </div>
                <div class="col-sm-8">
                	<nav class="navbar navbar-default" role="navigation">
          <div class="container-fluid"> 
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div id="top-menu">
              <div class="collapse navbar-collapse main_menu" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                  <li>
                      <a class="show-1 templatemo_home" href="#">
                      <span><i class="fa fa-home"></i><br/>Главная</span>
                      </a>
                  </li>
                  <li>
                      <a class="show-1 templatemo_page2" href="#">
                      <span><i class="fa fa-graduation-cap"></i><br/>Обо мне</span>
                      </a>
                  </li>
                  <li>
                      <a class="show-1 templatemo_page3" href="#">
                      <span><i class="fa fa-users"></i><br/>Услуги</span>
                      </a>
                  </li>
                  <li>
                      <a class="show-1 templatemo_page4" href="#">
                      <span><i class="fa fa-envelope"></i><br/>Контакты</span>
                      </a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /.navbar-collapse --> 
          </div>
          <!-- /.container-fluid --> 
        </nav>
               <div itemscope itemtype="http://schema.org/Person">
               <em itemprop="makesOffer" itemscope itemtype="http://schema.org/Offer">    <span itemprop="itemOffered" itemscope itemtype="http://schema.org/Service"><span itemprop="name">консультации</span></span>, <span itemprop="itemOffered" itemscope itemtype="http://schema.org/Service"><span itemprop="name">курсы</span></span>, <span itemprop="itemOffered" itemscope itemtype="http://schema.org/Service"><span itemprop="name">семинары</span></span>, <span itemprop="itemOffered" itemscope itemtype="http://schema.org/Service"><span itemprop="name">тренинги</span></span>, <span itemprop="itemOffered" itemscope itemtype="http://schema.org/Service"><span itemprop="name">круглые столы</span></span>
               </em>
               </div>
                </div>
                
            </div>
   	  </div>
      </div>
      </div>
        <!-- menu end -->
        
        
        <?php

	}
    
	function homePage($mainTxt, $linkService, $testmonials, $arrvideos){
//        if(is_array($tesmonials))
//            echo '<pre>';
//            print_r($testmonials);
//            echo '</pre>';
        ?>
        
        <div id="menu-container">
        
    <div class="content homepage" id="menu-1">
    
  	<div class="container">
       <a href="/login.php" id="auth">Авторизоваться</a>
        <!-- home start -->
        <div class="row">
        <div class="templatemo_homewrapper shadow">
        	<div class="templatemo_hometop_bg">
            	<div class="col-md-5 col-xs-4">
            	<div class="templemo_hometop_img"><img itemprop="logo" itemscope itemtype="http://schema.org/ImageObject" src="images/2692039.png" alt="templatemo home image" contentUrl="http://psicholog-ludmila.ru/images/2692039.png" /></div>
                </div>
                <div class="col-xs-8 hidden-md"></div>
            <div class="col-md-7">
                <div class="templatemo_hometop_title" itemprop="legalName">Персональный сайт психолога</div>
                <div class="templatemo_hometop_subtitle" itemscope itemtype="http://schema.org/Person">
                    <span itemprop="name">Пьянкова Людмила</span>
                </div>
                
                <?= $mainTxt ?>
                
            </div>
            <div class="clear"></div>
            </div>            
        </div>
        </div>
        <div class="clear"></div>
        <div itemscope itemtype="http://schema.org/Person" class="row">
        	<div itemscope itemtype="http://schema.org/LocalBusiness" class="templatemo_home_mid shadow">
        	
        	
        	<?php
            
            // вывод списка услуг на первую страницу
            for($i=0; $i<count($linkService); $i++){
                            
            ?>
        	
        	
        	<div class="col-md-3 templatemo_box gradient" itemprop="makesOffer" itemscope itemtype="http://schema.org/Offer">
            	<div class="boxsub1">
                  <div class="boxsub2">
                	   <img class="round" src="<?= $linkService[$i]['img'] ?>" alt="<?= $linkService[$i]['title'] ?>" title="<?= $linkService[$i]['title'] ?>"> 
                	
        <div itemprop="itemOffered" itemscope itemtype="http://schema.org/Service" class="templatemo_home_midheader"><span itemprop="name"><?= $linkService[$i]['title'] ?></span></div>

                <span class="main_menu">
                    <a href="###" id="but<?= $i+1 ?>"  class="templatemo_page3">
                        <div class="templatemo_readmore gradient">Подробнее</div>
                    </a>
                </span>

               	  </div>
                </div>
            </div>
            
            <?php } ?>
            
           	
            </div>
        </div>
        <div class="clear"></div>
        <div class="row">
        	<div class="templatemo_home_bot shadow">

            	<div class="col-md-12 col-xs-12 gradient padd-top20" itemscope itemtype="http://schema.org/Movie">
               
               <div class="col-md-6 col-xs-12">
                  
               <div id="video12">
                    <iframe src="http://www.youtube.com/embed/jI9LCnX_XcY?rel=0" allowfullscreen="" frameborder="0"></iframe>

               </div>
                 
              </div>
               
                <div class="col-md-2 col-xs-3">
                    <img class="imageCat" src="images/uokql-2-4.jpg" alt="Полезное видео по психоогии" title="Полезное видео по психоогии">
                </div>
                <div class="col-md-4 col-xs-9">
                <div id="botheader" class="templatemo_home_botheader">Материнство как ресурс поддержки детства на современном этапе</div>
                <div id="botsubheader" class="templatemo_home_botsubheader">Пьянкова Людмила</div>
                Сейчас удобно не только читать статьи, но и просматривать видеоролики на психологические темы. У меня для вас всегда открыт канал на Youtube <a href="https://www.youtube.com/channel/UCCUILG19M3aIbdZYr-Yi7tQ" title="самые насущные психологические темы">Людмила Пьянкова</a>. Добро пожаловать! 
				</div>
               
                <div class="clear"></div>
                
                
                <div class="col-md-12">
                                        
                    <div class="slider">
                      <div class="slide-list">
                        <div class="slide-wrap">
                          
                          <?php
                            for($i=0; $i<count($arrvideos); $i++){
                                if($arrvideos[$i]['view'] == 1){
                            ?>
                          
                          <div class="slide-item">
                            
                            <img itemprop="image" src="http://img.youtube.com/vi/<?= $arrvideos[$i]['url'] ?>/1.jpg" alt="<?= $arrvideos[$i]['title'] ?>" title="<?= $arrvideos[$i]['title'] ?>" tabindex="2" >
                            
                            <span itemprop="name" class="slide-title"><?= $arrvideos[$i]['title'] ?></span>
                            <span class="autor"><?= $arrvideos[$i]['author'] ?></span>
                          </div>
                          
                          <?php
                                }
                            }
                            
                            ?>
                        
		
                        </div>
                        <div class="clear"></div>
                      </div>
                      <div name="prev" class="navy prev-slide">
                          <span><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                      </div>
                      <div name="next" class="navy next-slide">
                          <span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                      </div>
                    <!--  <div class="auto play"></div>-->
                    </div>
                    
                </div>
                
                <!-- Testimonials (ОТЗЫВЫ) -->
        <div class="testimonials container">
            <div class="testimonials-title">
<!--                <h3>Отзывы</h3>-->
                <div class="templatemo_about_title">Отзывы</div>
            </div>
            <div class="row">
               
                <div class="testimonial-list span12">
                    <div class="tabbable tabs-below">
                       <div class="col-md-1"></div>
<!--                       tab-content-->
                        <div class="col-md-10">
                         
                    <?php
                    if(is_array($testmonials)){
        
                        for($i=0; $i<count($testmonials); $i++){
                            if($testmonials[$i]['view'] == 1){
                    ?> 
                         
                        <div class="testimonial">
                            <div class="spoiler-head heightBlock">"<?= $testmonials[$i]['head'] ?></div>
                            <div class="spoiler-body"><?= htmlspecialchars_decode($testmonials[$i]['body']) ?>"</div>
                            <br />
                            <span class="testBlue"><?= $testmonials[$i]['name'] ?></span><br/>
                            <span class="testBlue"><?= strftime('%d.%m.%Yг.',$testmonials[$i]['dateadd']) ?></span>
                        </div>
                         
                    <?php
                            }
                        }
                    }
                    ?>
                         
                        </div>
                        
                        <div class="col-md-1"></div>
                        

                   </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">

                    <div class="err"></div>
                    <div class="suc"></div>

            <form name="addtestmon" id="addtestmon" class="form form-register1" novalidate>

                <div class="control-group">
                    <div class="controls">
                    <input type="text" class="form-control" placeholder="Ваше имя" name="name" id="name" required data-validation-required-message="Представьтесь пожалуйста" />
                      <div class="help-block"></div>
                    </div>
                </div>  
                
                <div class="control-group">
                    <div class="controls">

                     <textarea name="message" placeholder="Ваш отзыв" id="message" rows="6" data-validation-required-message="Необходимо написать отзыв"></textarea>  

                    </div>
                </div>

               <button id="sendtestm" type="submit" name="send" class="mainBtn blue" title="Оставьте Ваш отзыв о работе психолога" >Добавить новый отзыв</button>
   
            </form>
                    
                    
                    
                    
                    
                    
                    
                    
                </div>
                <div class="col-md-2"></div>
            </div>
            
        </div>
<!--    ОТЗВЫ конец    -->
                        
                
               
                </div>
            </div>
        </div><!--   END ROW     -->

<!--
        <div class="row">
        	<div class="templatemo_home_bot shadow">
            	<div class="col-md-12 gradient padd-top20">
               
                <div class="col-md-2">
                	<img src="images/templatemo_home_img1.jpg" alt="templatemo home image">
                </div>
                <div class="col-md-4">
                <div class="templatemo_home_botheader">ENEAN SOLCITUD</div>
                <div class="templatemo_home_botsubheader">DUIS SED ODIO SIT AMET NIBH</div>
                Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet.
				</div>
               
                <div class="col-md-2"><img src="images/templatemo_home_img2.jpg" alt="templatemo home image"></div>
                <div class="col-md-4">
                <div class="templatemo_home_botheader">MAURIS LUCTUS</div>
                <div class="templatemo_home_botsubheader">AENEAN ELIT TURPIS</div>
                Praesent nunc tellus, laoreet sit amet, viverra sed, dictum semper, odio. Nunc malesuada. Ut lacinia euismod nunc. Mauris velit. 
				</div>
                <div class="clear"></div>
                <div class="col-md-2">
                	<img src="images/templatemo_home_img3.jpg" alt="templatemo home image">
                </div>
                <div class="col-md-4">
                <div class="templatemo_home_botheader">AENEAN SAPINE</div>
                <div class="templatemo_home_botsubheader">DUIS SED ODIO SIT AMET NIBH</div>
                Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet.
				</div>
                <div class="col-md-2"><img src="images/templatemo_home_img4.jpg" alt="templatemo home image"></div>
                <div class="col-md-4">
                <div class="templatemo_home_botheader">VESTIBULUM ANTE IPSUM</div>
                <div class="templatemo_home_botsubheader">AENEAN ELIT TURPIS</div>
                Praesent nunc tellus, laoreet sit amet, viverra sed, dictum semper, odio. Nunc malesuada. Ut lacinia euismod nunc. Mauris velit. 
				</div>
                </div>
            </div>
        </div>
-->
        
    
        
   </div>
   </div><!-- homepage end -->
        
        <?php

	}
    
    function IndexContent($head, $slogan){
        ?>
        <!-- Site Description -->
        <div class="presentation container">
            <h2><?= $head ?></h2>
            <p><?= $slogan ?></p>
        </div>
        
        <?php
        
        
    }
    
    function DescServices($head, $sDescTitle, $sdescHead, $sdesc){
        ?>
        <!-- Page Title -->
        <div class="page-title">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <i class="icon-tasks page-title-icon"></i>
                        <h2><?= $head ?> /</h2>
                        <p><?= $sDescTitle ?></p>
                    </div>
                </div>
            </div>
        </div>
    <!-- Services Full Width Text -->
        <div class="services-full-width container">
            <div class="row">
                <div class="services-full-width-text span12">
                    <h4><?= $sdescHead ?></h4>
                    <p><?= $sdesc ?></p>
                </div>
            </div>
        </div>
        
        <?php
    }
    
    function about($diploms){//вывод блока страницы ОБО МНЕ
        ?>
        
        <!-- gallery start -->
            <div class="content gallery" id="menu-2">
            <div class="container" itemscope itemtype="http://schema.org/Person">
<!--            	<div class="row gradient templatemo_gallery_wrapper">-->
                   
                   
                   <div class="row templatemo_about_wrapper">
                	<div class="col-md-12 gradient">
                    	<div class="templatemo_about">
                    	<div class="templatemo_about_title">Образование и дипломы</div>
                            <div class="templatemo_about_subtitle">Академическое образование</div>
                   
                       <p><span itemprop="alumniOf">
                    Новокузнецкий государственный педагогический институт</span><br/>
                        <span itemprop="alumniOf">
                    Академия дополнительного профессионального образования</span></p>
                   <ul>
                       <li><span itemprop="award">Диплом о профессиональной переподготовке</span></li>
                       <li><span itemprop="award">Аттестат доцента на кафедре психологии и педагогики</span></li>
                       <li><span itemprop="award">Диплом Новокузнецкого государственного педагогического института</span></li>
                       
                   </ul>
                    <p>Занимаемая должность: <span itemprop="jobTitle">преподаватель на кафедре Новокузнецкого государственного педагогического института</span></p>
                         
                         
                          <div class="templatemo_about_subtitle">Индивидуальный психотерапевтический опыт</div>
                      <p itemprop="makesOffer">Веду частную практику и оказываю профессиональную психологическую помощь: индивидуальные психологические консультации для взрослых, консультирование пар. Краткосрочная помощь в решении проблем, длительная психотерапия.</p>
                          <div class="templatemo_about_subtitle">Клиентская практика</div>
                      <p>Моими клиентами являются мужчины, женщины, супружеские и любовные пары, семьи с детьми.</p>
                      </div>
               	  </div>
                </div>
                
                <div class="row">                
                      <div class="col-md-12 templatemo_who">
                      		<div class="templatemo_about_title">Дипломы и сертификаты</div>
                            <div class="templatemo_about_text">

                    <?php
        if(is_array($diploms)){
            for($i=0; $i<count($diploms); $i++){
                if($diploms[$i]['view'] == 1){
                     ?>
                    
                  <div class="col-sm-3">
                    <div class="templatemo_gallery">
                        <div class="gallery-item">
							<img src="images/gallery/<?= $diploms[$i]['img'] ?>" alt="<?= $diploms[$i]['title'] ?>" title="<?= $diploms[$i]['title'] ?>">
							<div class="overlay">
								<a href="images/gallery/<?= $diploms[$i]['img'] ?>" data-rel="lightbox" class="fa fa-search" title="<?= $diploms[$i]['title'] ?>"></a>
							</div>
						</div>
                    </div>
                  </div>  
                    
                    <?php
                }
            }
        }
                     ?>
      
                            </div>
                     
                      </div>
                      
                </div>  <!-- row end -->
                </div>  <!-- conteiner end -->
            </div> <!-- content-gallery id=menu-2 end -->
        
        <?php
    }
    
    
    
    function Services($arrFullServ, $arrPriceTab){
        ?>
        
        <!-- about start -->
            <div class="clear"></div>
            <div class="content about" id="menu-3">
            <div class="container" itemscope itemtype="http://schema.org/LocalBusiness">
            
            	<div class="row templatemo_about_wrapper">
                	<div class="col-md-12 gradient">
                    	<div class="templatemo_about">
                    	<div class="templatemo_about_title">Консультация психолога</div>
                        <div class="templatemo_about_subtitle">Психологическое консультирование: что это?</div>
                      <p>
                          Психологическое консультирование — профессиональная помощь в поиске разрешения проблемной ситуации, ориентированное на людей, попавших в сложную жизненную ситуацию или желающих улучшить качество жизни.
                      </p>
                      <p>
                          Потребность в психологической помощи возникает, когда понимаешь, что не получается справиться самостоятельно с возникшими или уже ставшими хроническими трудностями. Чаще всего это сопровождает подавленное депрессивное состояние, навязчивые мысли, страхи, тяжелые воспоминания.
                      </p>
                      <p>
                          Цель психологического консультирования — помощь в решении проблемы. Осознание и изменение малоэффективных моделей поведения, для принятия важных решений, достижения поставленных целей, и самое главное, для жизни в гармонии с собой и окружающим миром. 
                      </p>
                      <p>
                          Психолог <a href="###">Людмила Пьянкова</a> помогает людям найти свои внутренние ресурсы, осознать ранее подавленные переживания и стереотипы поведения. На психологической консультации люди понимают причины своих трудностей и учатся с ними справляться.
                      </p>
                      </div>
               	  </div>
                </div>
                <div class="row">                
                      <div itemscope itemtype="http://schema.org/Service" class="col-md-12 col-xs-12 templatemo_who">
                            <meta itemprop="serviceType" content="Услуги частного психолога" />
                      		<div class="templatemo_about_title">Чем я могу помочь?</div>
                            
                            
            <div itemprop="hasOfferCatalog" itemscope itemtype="http://schema.org/OfferCatalog" class="container">
            
        <?php
        
        if(!is_array($arrFullServ)) return false;
        else{
            
            for($i=0; $i<count($arrFullServ); $i++){
            
        ?>    
              
       <div itemprop="itemListElement" itemscope itemtype="http://schema.org/Offer" class="panel panel-default">
        <div class="panel-heading">
<!--   id="but11" - должен быть у тех, которые надо открывать      -->
          <div itemprop="itemOffered" itemscope itemtype="http://schema.org/Service" id="but11" role="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse"><span itemprop="name"><?= $arrFullServ[$i]['title'] ?></span></div>
        </div>
        <div class="panel-collapse collapse out">
          <div class="panel-body">
           <img src="<?= $arrFullServ[$i]['img'] ?>" class="psychelp shadow" alt="<?= $arrFullServ[$i]['title'] ?>" title="<?= $arrFullServ[$i]['title'] ?>">
            <?= htmlspecialchars_decode($arrFullServ[$i]['description']) ?>
          </div>
        </div>
      </div>     
             
     <?php       
            }

        }    
     ?> 
      
    </div>
     
                      </div>
                </div>
                
                
                <div class="row gradient bot_box">
                	<div class="col-md-12">
                    	<div class="templatemo_bot_box">       	 	   	 	   	 	
                    	 	 	 	 	
            <table itemscope itemtype="http://schema.org/Invoice">
               <caption itemprop="description">Цены на услуги психолога</caption>
                <thead>
                    <tr>
                        <th>Услуга</th>
                        <th>Стоимость</th>
                        <th>Продолжительность</th>
                    </tr>
                </thead>
                <tbody>
                   
                   <?php
                    
                    if(!is_array($arrPriceTab)) return false;
                    else{
                        for($i=0; $i<count($arrPriceTab); $i++){        
                    ?>
                   <tr>
                       
                        <td itemprop="referencesOrder" itemscope itemtype="http://schema.org/Order"><span itemprop="orderedItem" itemscope itemtype="http://schema.org/Service"><span itemprop="description"><?= $arrPriceTab[$i]['title'] ?></span></span></td>
                       
                        <td itemprop="minimumPaymentDue" itemscope itemtype="http://schema.org/PriceSpecification"><span itemprop="price"><?= $arrPriceTab[$i]['price'] ?></span><span itemprop="priceCurrency">&nbsp;руб.</span></td>
                        <td><?= $arrPriceTab[$i]['duration'] ?></td>

                    </tr>
                   
                   <?php
                        }
                    }
                    ?>
  
                </tbody>
            </table>
                         
                  		</div>
                    </div>
                </div>
                
                
            </div>
            </div>
            <!-- about end -->
        
        <?php
    }
    
    function FullServices($arr){
        $arrInfo = $this -> info -> FullInfoServ();//массив текста для страницы услуги
        ?>
        <!-- Services Half Width Text -->
        <div class="services-half-width container">
            <div class="row">
               
               <?php for($i=0; $i<count($arrInfo); $i++){ ?>
               
                <div class="services-half-width-text span6">
                    <h4><?= $arrInfo[$i]['title'] ?></h4>
                    <p><?= $arrInfo[$i]['content'] ?></p>
                </div>
               
               <?php } ?>
                
            </div>
        </div>

        <!-- Call To Action -->
        <div class="call-to-action container">
            <div class="row">
                <div class="call-to-action-text span12">
                    <div class="ca-text">
                        <p><?= $arr[0] ?></p>
                    </div>
                    <div class="ca-button">
                        <a href="<?= $arr[1] ?>">Try It Now!</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
    }
    
    function GetPortfolio($arrCont, $portfInfo, $category, $dev){
        $dev .= "\n\t\t\t";//разделитель категорий
        ?>
        <!-- Page Title -->
        <div class="page-title">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <i class="icon-camera page-title-icon"></i>
                        <h2><?= $arrCont['pageTitle'] ?> /</h2>
                        <p><?= $arrCont['sDescTitle'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio -->
        <div class="portfolio portfolio-page container">
            <div class="row">
                <div class="portfolio-navigator span12">
                    <h4 class="filter-portfolio">
                        <a class="all" id="active-imgs" href="#">All</a><?= $dev ?>
                        
                        <?php
                        $str = '';
                        for($j=0; $j<count($category); $j++){
                        
                            //вызываю метод заменяющий пробел и регистр в категории
                            $lowkey = IndexPage::toLowerCat($category[$j]['nameCat']);
                            $str .= "<a class='$lowkey' id='' href='#'>".$category[$j]['nameCat']."</a>$dev";
                            
                        }
                        echo substr($str,0,-(strlen($dev)));//удаляю послед разделитель
                        ?>

                    </h4>
                </div>
            </div>
            <div class="row">
                <ul class="portfolio-img">
                   
                   <?php for($i=0; $i<count($portfInfo); $i++){ ?>
                                       
                    <li data-id="p-<?= $portfInfo[$i]['id'] ?>" data-type="<?= $portfInfo[$i]['category'] ?>" class="span3">
                        <div class="work">
                            <a href="<?= $portfInfo[$i]['img'] ?>" rel="prettyPhoto">
                                <img src="<?= $portfInfo[$i]['img'] ?>" alt="<?= $portfInfo[$i]['title'] ?>">
                            </a>
                            <h4><?= $portfInfo[$i]['title'] ?></h4>
                            <p><?= $portfInfo[$i]['sdesc'] ?></p>
                        </div>
                    </li>
                    
                    <?php } ?>
                    
                </ul>
            </div>
        </div>
        
        <?php
        
        
    }
    
    function contacts($userdata, $works){
        ?>
        
        <!-- contact start -->
            <div class="content contact" id="menu-5">
            <div id="menu-4" class="container">
            	<div class="row gradient templatemo_contact_wrapper">
                	<div class="col-md-12">
                    	<div class="templatemo_contact_map">
                    	<div id="templatemo_map">
                    	    
                    	    <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=YbiI968m_rkUscHQRs7PsJFdwWIj3uEH&amp;width=100%&amp;height=400&amp;lang=ru_RU&amp;sourceType=constructor&amp;scroll=true"></script>
                    	    
                    	</div>
                        </div>
                    </div>
					<div class="col-md-12">
                        <div class="templatemo_contact_title">Задать вопрос</div>
                        <div class="templatemo_contact_subtitle">или оставить заявку на консультацию</div>
                    </div>
                    <div class="col-md-6">
                    	<div class="templatemo_form">
                           
                            <div id="success"></div>

           <form name="sentMessage" class="form form-register1" id="contactForm" novalidate>

                <div class="control-group">
                    <div class="controls">
                    <input type="text" pattern="[a-zA-Zа-яА-Я\s]{1,30}$" class="form-control email" placeholder="Ваше имя" name="name" id="namemail" required data-validation-required-message="Пожалуйста укажите ваше имя" />
                      <div class="help-block"></div>
                    </div>
                </div>  
                <div class="control-group">
                    <div class="controls">
                    <input type="text" pattern="^\+{0,1}[\s\d-]{1,18}$" class="form-control email" placeholder="Телефон" name="phone" id="phone" required data-validation-required-message="Пожалуйста, укажите Ваш номер телефона" />
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                    <input type="email" name="email" pattern="^[\w][\w\._-]*[\w_]*@(([\w]+[\w-]*[\w]+)*\.)+[a-z]{2,4}$" class="form-control email" placeholder="E-mail" id="email" data-validation-required-message="Пожалуйста, укажите действительный e-mail" />
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">

                     <textarea name="message" placeholder="Сообщение" id="messagemail" rows="6" data-validation-required-message="Необходимо написать сообщение"></textarea>  

                    </div>
                </div>

               <button type="submit" name="send" class="mainBtn blue" title="Бесплатно задайте Ваш вопрос психологу" >Отправить сообщение</button>
   
            </form>
                        
                        </div>
                    </div>
                    <div class="col-md-6" itemscope itemtype="http://schema.org/Person">
                    	<div class="templatemo_form">
                         
                         
                          <ul itemprop="makesOffer" itemscope itemtype="http://schema.org/Offer">
                          <?php
                            if(is_array($works)){
                                for($i=0; $i<count($works); $i++){    
                            ?>
                               
                                <li itemprop="itemOffered" itemscope itemtype="http://schema.org/Service"><p itemprop="name" class="bluetext"><?= $works[$i]['title'] ?></p></li>
                                
                            
                            <?php
                                }
                            }
                            ?>
                         
                    	   </ul>

                           
                           <?php
                            if(is_array($userdata)){     
                            ?>
                           
                           
                        	<ul>
                               <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
<!--                                <div itemscope itemtype="http://schema.org/Country">-->
                            	<li>
                            	    <span class="fa fa-map-marker"></span>
                                    <span itemprop="addressRegion">Россия,</span>
                                    <span itemprop="addressLocality"> Кемеровская область, Новокузнецк</span>
                                </li>
<!--                              </div>-->
                               </span>
                                <li>
                                    <span class="fa fa-phone"></span>
                                    <span itemprop="telephone"><?= $userdata[0]['phone'] ?></span>
                                </li>
                                <li>
                                    <span class="fa fa-youtube-play"></span>
                                    Мой видеоканал на <a itemprop="url" class="redtext" href="<?= $userdata[0]['youtube'] ?>" target="_blank" title="самые насущные психологические темы">Youtube</a></li>
                                <li>
                                   <span class="fa fa-skype"></span>
                                    Мой skype: <a href="skype:<?= $userdata[0]['skype'] ?>?call" itemprop="skype"><?= $userdata[0]['skype'] ?></a>
                                </li>
                                <li>
                                    <span class="fa fa-envelope"></span>
                                    Мой email: <a href="mailto:<?= $userdata[0]['email'] ?>" itemprop="email"><?= $userdata[0]['email'] ?></a>
                                </li>
                                
                                
                            </ul>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                                            
                    
                </div>
            </div>
            </div>
            <!-- contact end -->
			</div> <!--   END menu-container  -->
        
    <?php
    }
    
    
    
        
} // END of Class HTMLPage
 ?>   
    