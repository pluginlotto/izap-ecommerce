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

global $CONFIG;

$options['type'] = 'object';
$options['subtype'] = 'izap_ecommerce';
$options['full_view'] = FALSE;
echo elgg_list_entities($options);