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

$r_type = get_input('r_type');
$attrib_key = get_input('key');
$group_key = get_input('g_key');
$product = get_entity(get_input('guid'));
if($product) {
  $attrib_groups = $product->getAttributeGroups();
  if($r_type == 'group') {
    $attribs  = unserialize($product->attribs);
     unset ($attribs[$group_key]);
     $product->attribs = serialize($attribs);
    unset ($attrib_groups[$group_key]);
    $product->attrib_groups = serialize($attrib_groups);
  }elseif($r_type == 'attrib'){
     $attribs  = unserialize($product->attribs);
     unset ($attribs[$group_key][$attrib_key]);
     $product->attribs = serialize($attribs);
  }
}
forward($_SERVER['HTTP_REFERER']);
exit;