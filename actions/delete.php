<?php
/**************************************************
* iZAP Web Solutions                              *
* Copyrights (c) 2005-2009. iZAP Web Solutions.   *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Kumar<tarun@izap.in>"
 */

admin_gatekeeper();
global $IZAP_ECOMMERCE;
$entity = get_entity(get_input('guid'));

if($entity->delete()) {
  system_message(__('product_deleted'));
}else{
  register_error(__('product_not_deleted'));
}
forward($IZAP_ECOMMERCE->link);
exit;