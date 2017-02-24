<form id="formPrice" action="" method="post">
       
            <table>
               <caption>Личные данные</caption>
                <thead>
                    <tr>
                        
                        <th>Название</th>
                        <th>Контакт</th>
                        
                    </tr>
                </thead>
                <tbody>
                <?php
                    
                    foreach($this -> data as $item){
                ?>
                    <tr> 
                        <td>    
                            <p>Адрес:</p>
                        </td>
                        <td>
                           <input type="hidden" name="id_0" value="<?= $item->id ?>" />
                            <input class="inptitledesc<?php if(!empty($this->data[$i]->addr_err)){?> error<?php } ?>" type="text" name="addr_0" value="<?= $item->addr ?>" />
                        </td>
                    </tr>
					<tr> 
                        <td>    
                            <p>Телефон:</p>
                        </td>
                        <td>
                            <input class="inptitledesc<?php if(!empty($this->data[$i]->phone_err)){?> error<?php } ?>" type="text" name="phone_0" value="<?= $item->phone ?>" />
                        </td>
                    </tr>
                    
                    <tr> 
                        <td>    
                            <p>Skype:</p>
                        </td>
                        <td>
                            <input class="inptitledesc<?php if(!empty($this->data[$i]->srype_err)){?> error<?php } ?>" type="text" name="skype_0" value="<?= $item->skype ?>" />
                        </td>
                    </tr>
                    
                    <tr> 
                        <td>    
                            <p>Email:</p>
                        </td>
                        <td>
                            <input class="inptitledesc<?php if(!empty($this->data[$i]->email_err)){?> error<?php } ?>" type="text" name="email_0" value="<?= $item->email ?>" />
                        </td>
                    </tr>
                    <tr>
                       <td colspan="2"><hr/></td>         
                    </tr>
                    
                <?php
                        
                   }
                ?>
                
                </tbody>
            </table>
       <input class="buttsave" type="submit" name="save-<?= $this->func ?>" value="Сохранить изменения">
</form>