<form id="formPrice" action="" method="post">
       
            <table>
               <caption>Оказываемые услуги</caption>
                <thead>
                    <tr>
                        
                        <th>Название услуги</th>
                        <th>&nbsp;</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                <?php
                    for($i=0; $i<count($this->data); $i++){      
                ?>
                    <tr>
                       
                        <td>
                            <input type="hidden" name="id_<?= $i ?>" value="<?= $this->data[$i]['id'] ?>" />
							<input class="inptitledesc" type="text" name="title_<?= $i ?>" value="<?= $this->data[$i]['title'] ?>" />
							
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>?ctrl=4&idwork=<?= $this->data[$i]['id'] ?>">Удалить</a>
                        </td>
                    </tr>
                    
                    
                <?php
                    }
                    //}
                    if(isset($_POST['addwork'])){
                        $i = count($this->data);// количество элементов
                ?>
                        <tr> 
                            <td>
                                <input class="inptitledesc" type="text" name="title_<?= $i ?>" />
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                
                
                <?php
                    
                    }
                    
                    ?>
                    <tr>
                       <td colspan="2"><hr/></td>         
                    </tr>
                </tbody>
            </table>
       <input class="buttsave" type="submit" name="addwork" value="Добавить новую услугу">
       <input class="buttsave" type="submit" name="savework" value="Сохранить изменения">
</form>