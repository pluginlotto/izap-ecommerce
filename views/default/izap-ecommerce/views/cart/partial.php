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
$cart = $vars['cart'];
$remove_lnk = $vars['url'] . 'action/izap_ecommerce/remove_from_cart?guid=';

?>
<div class="izap-cart-partial">
  <h3 class="izap-cart-title">
    <?php echo (__('my_cart'));?>
  </h3>
  <ol style="margin:0">
    <?php
    $total_price = 0;
    foreach($cart as $guid) {
      $product = get_product_izap_ecommerce($guid);
      if($product) {
        $dots = '';
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
        $remove_link = '<a href="'.$remove_link.'" class="izap-product-remove-from-cart" title="Remove from carr">X</a>'
                ?>
    <li style="margin:0;padding:0;" class="izap-partial-cart-product">
          <?php echo $remove_link;?>:<a href="<?php echo $product->getUrl()?>" title="<?php echo $product->title; ?>" class="">
            <?php echo $title . $dots;?>
      </a>
      [<?php echo $price;?>]

    </li>
        <?php
        $total_price += $product->getPrice(FALSE);
      }
    }
    ?>
  </ol>
  <div class="contentWrapper izap-product-buy" style="padding:1px 5px 1px 1px;margin: 2px;">
    <p style="margin:1px;padding:1px;">
      <a href="<?php echo $IZAP_ECOMMERCE->link?>cart">
        <?php _e('checkout');?>
      </a>
      <b class="izap-product-float-right">
        <?php echo __('total') . ': ' . $IZAP_ECOMMERCE->currency_sign . $total_price;?>
      </b>
    </p>
  </div>
</div>