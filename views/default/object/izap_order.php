<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version 1.0
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

global $IZAP_ECOMMERCE;
$order = $vars['entity'];
?>
<div class="<?php echo ($order->confirmed == 'no') ? 'unconfirmed_order' : 'confirmed_order'?>">
  <a href="<?php echo $IZAP_ECOMMERCE->link?>order_detail/<?php echo $order->guid?>/">
    <div class="izap-product-float-left" style="width: 50%">
      #<b><?php echo $order->guid;?></b>
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
  </a>
</div