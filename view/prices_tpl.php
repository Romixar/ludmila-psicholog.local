<?php for($i=0; $i<count($data); $i++){ ?>
<tr>
   <td itemprop="referencesOrder" itemscope itemtype="http://schema.org/Order"><span itemprop="orderedItem" itemscope itemtype="http://schema.org/Service"><span itemprop="description"><?= $data[$i]->title ?></span></span></td>
                     
   <td itemprop="minimumPaymentDue" itemscope itemtype="http://schema.org/PriceSpecification"><span itemprop="price"><?= $data[$i]->price ?></span><span itemprop="priceCurrency">&nbsp;руб.</span></td>
   
   <td><?= $data[$i]->duration ?></td>

</tr>
<?php } ?>