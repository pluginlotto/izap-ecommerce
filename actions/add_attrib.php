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

if(!izap_is_ajax_request()) {
  forward($_SERVER['HTTP_REFERER']);
  exit;
}

$attrib_name = get_input('attrib_name');
$attrib_value = get_input('attrib_value');
$attrib_desc = get_input('attrib_help');
$group_id = get_input('group_id');
$product = get_entity(get_input('product_guid'));
if($product && $product->canEdit()) {
  $attribs  = unserialize($product->attribs);
  $attribs[$group_id][] = array(
          'name' => $attrib_name,
          'value' => $attrib_value,
          'description' => $attrib_desc,
  );

  $attrib_groups = $product->getAttributeGroups();
  $product->attribs = serialize($attribs);
  echo IzapEcommerce::createAttributes(array(
  'type' =>$attrib_groups[$group_id]['type'],
  'attribs' => $attribs[$group_id],
  ));
}
exit;