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
    <?php
    // link to add the new version
    if($product->canEdit() && !$product->isArchived()) {?>
    <div align="right" class="add_new_version">
      <a href="<?php echo func_set_href_byizap(array(
        'plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
        'page' => 'new_version',
        'vars' => array($product->guid)
           ));?>"><?php echo elgg_echo('izap_ecommerce:add_new_version'); ?></a>
    </div>
    <div class="clearfloat"></div>
      <?php }?>
    <div align="center">
      <img src="<?php echo $product->getIcon('master');?>" alt="<?php $product->title?>" class="izap-product-image"/>
    </div>
    <?php
    echo $product->description;
    ?>
  </div>

  <div class="clearfloat"></div>
  <p>
    <?php
    echo elgg_echo('izap-ecommerce:tags');
    echo ': ' . elgg_view('output/tags', array('tags' => $product->tags));
    ?>
    <br />
    <?php
    echo elgg_view($IZAP_ECOMMERCE->product . 'edit_delete', array('entity' => $product));
    // show download count to owner
    if($product->canEdit()) {
      ?>
  <h3 align="right"><?php
      echo elgg_echo('izap-ecommerce:total_download') . ': ' . $product->getDownloads();
      ?></h3>
    <?php
  }
  ?>
</p>
</div>
