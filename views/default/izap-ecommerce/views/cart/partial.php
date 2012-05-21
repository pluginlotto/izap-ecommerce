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
$attributes = get_from_session_izap_ecommerce('izap_cart_attrib');
$cart = $vars['cart'];
$remove_lnk = $vars['url'] . 'action/izap_ecommerce/remove_from_cart?guid=';

?>
<div class="izap-cart-partial">
  <h3 class="izap-cart-title">
    <?php echo (elgg_echo('izap-ecommerce:my_cart'));?>
  </h3>
  <table width="100%">
    <?php 
    $total_price = 0;
    foreach($cart as $guid) {
     $product = get_product_izap_ecommerce($guid); 
      if($product) {
        $product_attribs = $attributes[$guid];
        $attrib_amount = array_sum($product_attribs);
        $dots = '';
        $int_price = $product->getPrice(FALSE) + $attrib_amount;
        $price = $product->getPrice();

        $total_length = 19;
        $price_lenght = strlen($price) + 2;

        $remain_length = $total_length - $price_lenght;

        $title = substr($product->title, 0, $remain_length);
        $title_length = strlen($title);

        $total_text = $title_length + $price_lenght;
        $remain_length = $total_length - $total_text;
        
        if($remain_length > 0) {
          for($i=1; $i< $remain_length; $i++)
          $dots .= '.';
        }
        
        $remove_link = elgg_add_action_tokens_to_url($remove_lnk . $product->guid);
        $remove_link = '<a href="'.$remove_link.'" class="izap-product-remove-from-cart" title="'.elgg_echo('izap-ecommerce:remove_from_cart').'"><img src="'.$vars['url'].'mod/'.GLOBAL_IZAP_ECOMMERCE_PLUGIN.'/_graphics/remove_from_cart.png" /></a>';
         ?>
    <tr>
      <td><?php echo $remove_link;?>&nbsp; <a href="<?php echo $product->getUrl()?>" title="<?php echo $product->title; ?>" class="">
            <?php echo $title . $dots;?>
      </a></td>
      <td align="right"><?php echo $IZAP_ECOMMERCE->currency_sign . $int_price;?></td>
    </tr>
        <?      
    $total_price += $int_price;
      }
    }
    ?>
  </table>
  
  <div  style="padding:1px 5px 1px 1px;margin: 2px;">
    <p style="margin:1px;padding:1px;">
      <a href="<?php echo $IZAP_ECOMMERCE->link?>cart" class= "elgg-button elgg-button-action">
        <?php echo elgg_echo('izap-ecommerce:checkout');?>
      </a>
      <b class="izap-product-float-right">
        <?php echo elgg_echo('izap-ecommerce:total') . ': ' . $IZAP_ECOMMERCE->currency_sign . $total_price;?>
      </b>
    </p>
  </div>
</div>