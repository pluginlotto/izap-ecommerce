<?php
/**************************************************
* iZAP Web Solutions                              *
* Copyrights (c) 2005-2009. iZAP Web Solutions.   *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Kumar<tarun@izap.in>"
 */


global $IZAP_ECOMMERCE;
$order = $vars['entity'];

?>
<div class="contentWrapper">
  <?php
  $odd_even = 1;
  for($i = 0; $i < $order->total_items; $i++):
    $item_name = 'item_name_' . $i;
    $item_price = 'item_price_' . $i;
    $item_guid = 'item_guid_' . $i;
    $class = ($odd_even%2 == 0) ? 'even' : 'odd';
    ?>
  <div class="izap-order-detait-<?php echo $class;?>">
    <div class="izap-product-float-left" style="width: 55%">
        <?php echo $order->$item_name ?>
    </div>

    <div class="izap-product-float-left" style="width: 20%">
         <a href="<?php echo create_product_download_link_izap_ecommerce($order, $order->$item_guid)?>">
           <?php _e('download');?>
        </a>
    </div>

    <div class="izap-product-float-right" style="width: 20%">
      <b><?php echo $IZAP_ECOMMERCE->currency_sign . $order->$item_price ?></b>
    </div>

    <div class="clearfloat"></div>
  </div>
    <?php
    $odd_even++;
  endfor;
  ?>
  <div class="izap-order-detait-total">
    <p align="right">
      <b>
        <?php
        _e('total');
        ?>
        : <?php echo $IZAP_ECOMMERCE->currency_sign . $order->total_amount?></b>
    </p>
  </div>
</div>
