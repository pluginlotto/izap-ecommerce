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
$product = $vars['entity'];
$add_cart_link = elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/add_to_cart?guid=' . $product->guid);
$add_wishlist_link = elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/add_to_wishlist?guid=' . $product->guid);
?>
<div class="contentWrapper izap-product-buy">
  <?php if(!$product->isArchived()) {?>
  <div class="izap-product-float-left izap-product-buy-rate">
    <b>
        <?php
        echo elggb_echo('rateit');
        ?>
    </b>
    <br />
    <span id="rate_stars">
        <?php echo elgg_view('input/rate', array('entity' => $product));?>
    </span>
  </div>

  <div class="izap-product-float-left izap-product-buy-price">
      <?php $price = $product->getPrice(false);
      if($price) {?>
    <b>
          <?php
          if(isloggedin()) {
            echo elgg_echo('izap-ecommerce:price');
          }else {
            echo elgg_echo('izap-ecommerce:price_not_more');
          }
          ?>
    </b><br />
        <?php
        echo '<b>' . $product->getPrice() . '</b>';
      }
      ?>
  </div>

  <div class="izap-product-float-right izap-product-buy-buynow">
      <?php if($product->isAvailable()) {
        if($product->canDownload()) {
          $donwload_link = create_product_download_link_izap_ecommerce(rand(0, 1000), $product->guid);
          ?>
    <a href="<?php echo $donwload_link?>">
            <?php echo elgg_echo('izap-ecommerce:download');?>
    </a>
          <?php
        }else {
          ?>
    <a href="<?php echo $add_cart_link?>">
            <?php echo elgg_echo('izap-ecommerce:buynow');?>
    </a>
          <?php

          if(IzapEcommerce::isInWishlist($product->guid)) {
            ?>
    <a href="<?php echo elgg_add_action_tokens_to_url(func_get_actions_path_byizap(array('plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN)) .
            'remove_from_wishlist?guid=' . $product->guid);
               ?>">
                 <?php echo elgg_echo('izap-ecommerce:remove_from_wishlist');?>
    </a>
            <?php
          }elseif(isloggedin()) {?>
    &nbsp;
    <a href="<?php echo $add_wishlist_link;?>">
              <?php echo elgg_echo('izap-ecommerce:add_to_wishlist');?>
    </a>
            <?php }?>
          <?php }
      }else {?>
    <a href="#">
          <?php echo elgg_echo('izap-ecommerce:comming soon');?>
    </a>
        <?php }?>
  </div>

  <div class="clearfloat"></div>
    <?php
  }else {
    if(IzapEcommerce::isInWishlist($product->guid)) {
      ?>
  <div class="add_new_version">
    <a href="<?php echo elgg_add_action_tokens_to_url(func_get_actions_path_byizap(array('plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN)) .
        'remove_from_wishlist?guid=' . $product->guid);
           ?>">
             <?php echo elgg_echo('izap-ecommerce:remove_from_wishlist');?>
    </a>
  </div>
      <?php
    }
    $new_version = get_product_izap_ecommerce($product->parent_guid);
    if($new_version) {
      ?>
  <div class="add_new_version">
    <a href="<?php echo $new_version->getURL();?>"><?php echo 
          elgg_echo('izap-ecommerce:get_latest_version')?></a>
  </div>
      <?php }

    if($product->canDownload()) {
      $donwload_link = create_product_download_link_izap_ecommerce(rand(0, 1000), $product->guid);
      ?>
  <div class="old_version_download">
    <a href="<?php echo $donwload_link?>">
          <?php echo elgg_echo('izap-ecommerce:download');?>
    </a></div>
      <?php
    }

    ?>
  <div class="clearfloat"></div>
    <?php
  }

  ?>
</div>