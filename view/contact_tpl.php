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
                                                         
                              <?= $works ?>
                                                     
                    	  </ul>

                           
                                                      
                           
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
                                    <span itemprop="telephone">+7-913-403-94-75</span>
                                </li>
                                <li>
                                    <span class="fa fa-youtube-play"></span>
                                    Мой видеоканал на <a itemprop="url" class="redtext" href="https://www.youtube.com/channel/UCCUILG19M3aIbdZYr-Yi7tQ" target="_blank" title="самые насущные психологические темы">Youtube</a></li>
                                <li>
                                   <span class="fa fa-skype"></span>
                                    Мой skype: <a href="skype:duby.ludmila?call" itemprop="skype">duby.ludmila</a>
                                </li>
                                <li>
                                    <span class="fa fa-envelope"></span>
                                    Мой email: <a href="mailto:duby.ludmila@yandex.ru" itemprop="email">duby.ludmila@yandex.ru</a>
                                </li>
                                
                                
                            </ul>
                                                    </div>
                    </div>
                                            
                    
                </div>
</div>