<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
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
<div class="izap-product-buy">
  <?php if(!$product->isArchived()) {?>
  <div class="izap-product-float-left izap-product-buy-rate">
    <?php echo elgg_view('likes/display', array('entity'=>$product)) ?>
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
    </b>
        <?php
        echo '<b id="product_price_html">' . $product->getPrice() . '</b>';
      }
      ?>
  </div>
  <div class="clearfloat"></div>
</div>
<div class="izap-product-buy-buynow" >
    <?php if($product->isAvailable()) {
      if($product->canEdit()) {
        $form = elgg_view($IZAP_ECOMMERCE->forms.'add_attribute', array('entity'=> $product));
      }else {
        $form = elgg_view($IZAP_ECOMMERCE->product.'view_attributes', array('entity' => $product));
      }
      if($product->canDownload()) {
        $donwload_link = create_product_download_link_izap_ecommerce(rand(0, 1000), $product->guid);
        $form .= '<a class="button" href="'.$donwload_link.'">
            '.elgg_echo('izap-ecommerce:download').'</a>';
      }
      if($product->canEdit()) {
        echo $form;
      }else {
        if(!$product->canDownload()) {
          $form .= elgg_view('input/hidden', array('internalname' => 'product_guid', 'value' => $product->guid));


//          if(IzapEcommerce::isInWishlist($product->guid)) {
//            $form .= '
//            <a class="button" href="'.elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/' .
//                    'remove_from_wishlist?guid=' . $product->guid).'">'.elgg_echo('izap-ecommerce:remove_from_wishlist').'</a>
//          ';
//          }elseif(isloggedin()) {
//            $form .= '
//            <a class="button" href="'.$add_wishlist_link.'">'.elgg_echo('izap-ecommerce:add_to_wishlist').'</a>
//          ';
//          }

          $form .= elgg_view('input/submit', array('value' => elgg_echo('izap-ecommerce:buynow')));
        }
        echo elgg_view('input/form', array('body' => $form, 'action' => $vars['url'] . 'action/izap_ecommerce/add_to_cart'));
      }
      ?>
      <?php
    }else {?>
  <a class="button" href="#">
        <?php echo elgg_echo('izap-ecommerce:comming soon');?>
  </a>
      <?php }?>
</div>
<br />
  <?php
}else {
  if(IzapEcommerce::isInWishlist($product->guid)) {
    ?>
<div class="add_new_version">
  <a href="<?php echo elgg_add_action_tokens_to_url($IZAP_ECOMMERCE->actions . 'remove_from_wishlist?guid=' . $product->guid); ?>">
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
<script type="text/javascript">
  $(document).ready(function() {
    //Select all anchor tag with rel set to tooltip
    $('a[rel=tooltip]').mouseover(function(e) {
      //Grab the title attribute's value and assign it to a variable
      var tip = $(this).attr('title');
      //Remove the title attribute's to avoid the native tooltip from the browser
      $(this).attr('title','');

      //Append the tooltip template and its value
      $(this).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip + '</div><div class="tipFooter"></div></div>');

      //Set the X and Y axis of the tooltip
      $('#tooltip').css('top', e.pageY + 10 );
      $('#tooltip').css('left', e.pageX + 20 );

      //Show the tooltip with faceIn effect
      $('#tooltip').fadeIn('500');
      $('#tooltip').fadeTo('10',0.8);

    }).mousemove(function(e) {

      //Keep changing the X and Y axis for the tooltip, thus, the tooltip move along with the mouse
      $('#tooltip').css('top', e.pageY + 10 );
      $('#tooltip').css('left', e.pageX + 20 );

    }).mouseout(function() {

      //Put back the title attribute's value
      $(this).attr('title',$('.tipBody').html());

      //Remove the appended tooltip template
      $(this).children('div#tooltip').remove();

    });
  });
</script>














<div class="product-all">

        <div class="izap-product-extra">
      <?php
      echo elgg_view('likes/display', array('entity' => $product));
      ?><br/>
        <span class="wishlist">
          <?php
          echo elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/buy_options/wishlist', array('entity' => $product));
          ?>
        </span>
        </div>
           </div>
<div class="clearfloat"></div>
      <?php
//      $attrib_html = elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/view_attributes', array('entity' => $product));
//      if(show_buy_now == 'yes') {
      ?>
<!--        <a href="<?php echo $cart_url?>" id="post_cart_1" class="elgg-button elgg-button-action"><img src ="<?php echo $vars['url'] . 'mod/' . GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/_graphics/add_to_cart.png' ?>" /><span class="add_to_cart"><?php echo elgg_echo('izap-ecommerce:add_to_cart')?></span></a>-->
        <?php
      //}
      //if($product->getprice(false)>0)
          echo $attrib_html;

      ?>