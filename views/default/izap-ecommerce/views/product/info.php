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
?>
<div>
  <div class="izap-product-description">
    <div align="center">
      <a href="#" onclick="javascript: window.open(
        '<?php echo $product->getIcon('orignal');?>',
        '<?php echo friendly_title($product->title)?>',
        'toolbar=no,directories=no,status=no,menubar=no')">
        <img src="<?php echo $product->getIcon('master');?>" alt="<?php $product->title?>" class="izap-product-image"/>
      </a>
    </div>
    <?php
    echo $product->description;
    ?>
  </div>

  <div class="clearfloat"></div>
  <p>
    <?php
    _e('tags');
    echo ': ' . elgg_view('output/tags', array('tags' => $product->tags));
    ?>
    <br />
    <?php echo elgg_view($IZAP_ECOMMERCE->product . 'edit_delete', array('entity' => $product));?>
  </p>
</div>
  