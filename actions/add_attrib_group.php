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

$group_name = get_input('attrib_group');
$group_type = get_input('attrib_type');
$pro_guid = get_input('product_guid');
$product = get_entity($pro_guid);
if($product && $product->canEdit()) {
  $attrib_groups = $product->getAttributeGroups();
  $attrib_groups[] = array(
          'name' => $group_name,
          'type' => $group_type,
  );

  $product->attrib_groups = serialize($attrib_groups);
}
forward($_SERVER['HTTP_REFERER']);
exit;

