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
$cart = $vars['cart'];
$attributes = get_from_session_izap_ecommerce('izap_cart_attrib');
$buy_link = $vars['url'] . 'action/izap_ecommerce/buy';
$remove_lnk = $vars['url'] . 'action/izap_ecommerce/remove_from_cart?guid=';
?>
<div class="izapcontentWrapper">
  <div class="izap-product-cart">
    <?php
    $total_price = 0;
    $total_products = 1;
    $odd_even = 1;
    foreach($cart as $guid) {
      $product_attribs = $attributes[$guid];
      $product = get_product_izap_ecommerce($guid);
      $class = ($odd_even%2 != 0) ? 'even' : 'odd';
      if($product) {
        $attrib_amount = array_sum($product_attribs);
        $pro_price = ($product->getPrice(FALSE) + $attrib_amount);
        $remove_link = elgg_add_action_tokens_to_url($remove_lnk . $product->guid);
        $remove_link = '<a href="'.$remove_link.'" class="izap-product-remove-from-cart">X</a>'
                ?>
    <div class=" izapcontentWrapper <?php echo $class?>">
      <div class="izap-product-float-left">
        <a href="<?php echo $product->getUrl()?>" title="<?php echo $product->title; ?>">
          <img src="<?php echo $product->getIcon()?>" alt="<?php echo $product->title?>" />
        </a>
      </div>

      <div class="izap-product-cart-descrption izap-product-float-left">
        <h3><a href="<?php echo $product->getUrl()?>"><?php echo $product->title?></a></h3>
            <?php
            echo substr(filter_var($product->description, FILTER_SANITIZE_STRING), 0, 200);
            if($attrib_amount > 0) {
              echo '<ol>';
                foreach($product_attribs as $name => $val) {
                  echo '<li><em>'.$name.'</em></li>';
                }
              echo '</ol>';
            }
            ?>
      </div>

      <div class="izap-product-float-right">
        <b><?php echo $IZAP_ECOMMERCE->currency_sign . $pro_price;?></b>
        <br />
        <br />
        <a href="<?php echo elgg_add_action_tokens_to_url($remove_lnk . $product->guid)?>" title="<?php echo elgg_echo('izap-ecommerce:remove_from_cart')?>">
          <img src="<?php echo $IZAP_ECOMMERCE->_graphics?>remove_from_cart.png" alt="<?php echo elgg_echo('izap-ecommerce:remove_from_cart')?>"/>
        </a>
      </div>

      <div class="clearfloat"></div>
    </div>
        <?php
        $item[$total_products]['name'] = $product->title;
        $item[$total_products]['amount'] = $pro_price;
        $item[$total_products]['guid'] = $product->guid;
        $item[$total_products]['code'] = $product->code;
        $item[$total_products]['attributes'] = $product_attribs;

        $total_products++;
        $total_price += $pro_price;
        $odd_even++;

        $user_guid = $product->owner_guid;
      }
    }
    ?>
  </div>
  <br />

  <div class="izapcontentWrapper izap-product-buy">
    <p align="right" style="margin:0;padding:0;">
      <b>
        <?php echo elgg_echo('izap-ecommerce:total') . ': ' . $IZAP_ECOMMERCE->currency_sign . $total_price;?>
      </b><br />
    </p>
    <div>
      <form action="<?php echo $buy_link?>" method="POST">
        <?php echo elgg_view('input/securitytoken');?>
        <?php
        echo elgg_view('input/payment_options', array(
        'user_guid' => $user_guid,
        ));

        echo elgg_view('input/hidden', array(
          'internalname' => 'owner_guid',
          'value' => $user_guid,
        ));
        if(is_plugin_enabled('izap-payment-gateways')) {
        ?>
        <input type="submit" value="<?php echo elgg_echo('izap-ecommerce:pay_now')?>" />
        <?php } else {
          echo elgg_echo('izap_payment:no_gateway_found');
        }
        ?>
      </form>
    </div>

    <div class="clearfloat"></div>
  </div>
</div>
<?php
//update session
add_to_session_izap_ecommerce('total_cart_items', $total_products);
add_to_session_izap_ecommerce('total_cart_price', $total_price);
add_to_session_izap_ecommerce('items', $item); ?>