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
  <div class="izap-product-float-left" style="width: 50%">
    #<a href="<?php echo $IZAP_ECOMMERCE->link?>order_detail/<?php echo $order->guid?>/">
      <b><?php echo $order->guid;?></b>
    </a>
  </div>

  <div class="izap-product-float-left" style="width: 30%">
    <?php
    _e('total_amount');
    ?>
    : 
    <b>
      <?php echo $IZAP_ECOMMERCE->currency_sign . $order->total_amount;?>
    </b>
  </div>

  <div class="izap-prouct-float-right">
    <?php
      echo date('d M Y', $order->time_created);
    ?>
  </div>

  <div class="clearfloat"></div>
</div>
