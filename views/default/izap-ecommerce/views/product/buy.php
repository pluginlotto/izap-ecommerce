<?php

/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
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
?>
<div class="izap-product-buy">
<?php
// if is comming soon
if (!$product->isAvailable()) {
  echo elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/buy_options/comming_soon', array('entity' => $product));
}else{

// render the archived or non archived version
if (!$product->isArchived())
  echo elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/buy_options/unarchived', array('entity' => $product));
else
  echo elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/buy_options/archived', array('entity' => $product));
}
?>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $('#post_cart_1, #post_cart_2').click(function (){
      $('#izap_cart_from').submit();
      return false;
    });
  });
</script>