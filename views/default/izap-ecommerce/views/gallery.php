<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version {version} $Revision: {revision}
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

$product = $vars['entity'];
?>
<div class="izap_ecommerce_product_gallery">
  <a href="<?php echo $product->getURL();?>">
    <div align="center">
      <img src="<?php echo $product->getIcon()?>" alt="<?php echo $product->title;?>" />
    </div>
    <div>
      <b>
        <?php echo substr($product->title, 0, 20) . '....';?>
      </b>
      <br />
      <?php
      if($product->getPrice(FALSE)) {
        echo '<b>' . __('price') . '</b>: ' . $product->getPrice() . '<br />';
      }else {
        echo '<b>' . __('free') . '</b><br />';
      }
      ?>
    </div>
    <div>
      <?php echo elgg_view('output/rate', array('entity' => $product));?>
    </div>
  </a>
</div>