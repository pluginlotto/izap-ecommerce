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
$entity = get_entity(get_input('guid'));

if($entity->delete() || delete_entity($entity->guid)) {
  system_message(elgg_echo('izap-ecommerce:product_deleted'));
}else{
  register_error(elgg_echo('izap-ecommerce:product_not_deleted'));
}
forward($IZAP_ECOMMERCE->link);
exit;