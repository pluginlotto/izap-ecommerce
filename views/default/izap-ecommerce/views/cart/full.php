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
$buy_link = $vars['url'] . 'action/izap_ecommerce/buy';
?>
<div class="contentWrapper">
  <div class="izap-product-cart">
    <?php
    $total_price = 0;
    $total_products = 1;
    $odd_even = 1;
    foreach($cart as $guid) {
      $product = get_product_izap_ecommerce($guid);
      $class = ($odd_even%2 != 0) ? 'even' : 'odd';
      if($product) {
        $remove_link = elgg_add_action_tokens_to_url($remove_lnk . $product->guid);
        $remove_link = '<a href="'.$remove_link.'" class="izap-product-remove-from-cart">X</a>'
                ?>
    <div class=" contentWrapper <?php echo $class?>">
      <div class="izap-product-float-left">
        <a href="<?php echo $product->getUrl()?>" title="<?php echo $product->title; ?>">
          <img src="<?php echo $product->getIcon()?>" alt="<?php echo $product->title?>" />
        </a>
      </div>

      <div class="izap-product-cart-descrption izap-product-float-left">
        <h3><a href="<?php echo $product->getUrl()?>"><?php echo $product->title?></a></h3>
        <?php echo substr(filter_var($product->description, FILTER_SANITIZE_STRING), 0, 200);?>
      </div>
      
      <div class="izap-product-float-right">
        <b><?php echo $product->getPrice();?></b>
      </div>

      <div class="clearfloat"></div>
    </div>
        <?php
        $item[$total_products]['name'] = $product->title;
        $item[$total_products]['amount'] = $product->getPrice(FALSE);
        $item[$total_products]['guid'] = $product->guid;

        $total_products++;
        $total_price += $product->getPrice(FALSE);
        $odd_even++;
      }
    }
    ?>
  </div>
  <br />

  <?php
  //echo elgg_view($IZAP_ECOMMERCE->forms . 'billing_info');
  ?>

  <div class="contentWrapper izap-product-buy">
    <p align="right" style="margin:0;padding:0;">
      <b>
        <?php echo __('total') . ': ' . $IZAP_ECOMMERCE->currency_sign . $total_price;?>
      </b><br />
    </p>
    <div class="izap-product-float-right">
      <form action="<?php echo $buy_link?>" method="GET">
        <?php echo elgg_view('input/securitytoken');?>
        <input type="submit" value="<?php _e('pay_now')?>" />
      </form>
    </div>

    <div class="clearfloat"></div>
  </div>
</div>
<?php
//update session
add_to_session_izap_ecommerce('total_cart_items', $total_products);
add_to_session_izap_ecommerce('total_cart_price', $total_price);
add_to_session_izap_ecommerce('items', $item);
?>