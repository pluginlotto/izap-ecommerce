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
$order_owner = $order->getOwnerEntity();
if(is_plugin_enabled('messages')) {
  $notify_link = $vars['url'] . 'mod/messages/send.php?send_to=' . $order_owner->guid;
}else{
  $notify_link = 'mailto:' . $order_owner->email;
}
?>

<div class="contentWrapper">
  <div class="order_detail_owner">

    <div class="icon">
      <a href="<?php echo $order_owner->getURL();?>">
        <?php
        echo elgg_view('profile/icon', array('entity' => $order_owner, 'override' => true));
        ?>
      </a>
    </div>

    <div class="info">
      <?php echo elgg_echo('izap-ecommerce:name'); ?>:
      <b>
        <a href="<?php echo $order_owner->getURL();?>">
          <?php echo $order_owner->name;?>
        </a>
      </b>
      <br />

      <?php echo elgg_echo('izap-ecommerce:email'); ?>:
      <b>
        <a href="<?php echo $notify_link;?>">
          <?php echo $order_owner->email;?>
        </a>
      </b>
      <br />

      <?php echo elgg_echo('izap-ecommerce:order_date'); ?>:
      <b>
        <?php echo date('d-n-Y', $order->time_updated);?>
      </b>
      <br />

      <?php echo elgg_echo('izap-ecommerce:order_comfirmed'); ?>:
      <b>
        <?php echo $order->confirmed;?>
      </b>
      <br />

      <?php echo elgg_echo('izap-ecommerce:payment_method'); ?>:
      <b>
        <?php 
        if(isadminloggedin()) {
          $method = ($order->payment_method) ? $order->payment_method : 'paypal';
        }else{
          $method = ($order->payment_method) ? $order->payment_method : 'paypal';
          $info = func_get_custom_value_byizap(array(
            'plugin' => GLOBAL_IZAP_PAYMENT_PLUGIN,
            'var' => 'gateways_info',
          ));

          $method = $info[$method]['title'];
        }
        echo $method;
        ?>
      </b>
      <br />

    </div>

  </div>
  <div class="clearfloat"></div>
</div>

<?php
echo elgg_view_title(elgg_echo('izap-ecommerce:order_details'));
?>
<div class="contentWrapper">
  <?php
  $odd_even = 1;
  for($i = 0; $i < $order->total_items; $i++):
    $item_name = 'item_name_' . $i;
    $item_price = 'item_price_' . $i;
    $item_guid = 'item_guid_' . $i;
    $class = ($odd_even%2 == 0) ? 'even' : 'odd';

    $product = get_entity($order->$item_guid);
    if($product) {

      $product_url = $product->getURL();
    }else {
      $product_url = '#';
    }
    ?>
  <div class="izap-order-detait-<?php echo $class;?>">
    <div class="izap-product-float-left" style="width: 55%">
      <a href="<?php echo $product_url?>">
          <?php echo $order->$item_name ?>
      </a>
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
