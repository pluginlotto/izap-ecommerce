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

$product = $vars['entity'];
$product_attrib_groups = $product->getAttributeGroups();
if($product_attrib_groups) {
  foreach($product_attrib_groups as $key => $group) {
    ?>
<fieldset><legend><?php echo $group['name']?></legend>
      <?php echo IzapEcommerce::createAttributes(array(
      'type' =>$group['type'],
      'group' => $group,
      'attribs' => $product->getAttribute($key),
      'entity' => $product,
      ));
      ?>
</fieldset>
    <?php
  }
}
?>
<br />
<div class="clearfloat"></div>