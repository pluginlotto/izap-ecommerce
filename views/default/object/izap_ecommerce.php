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
global $IZAP_ECOMMERCE;
if($vars['full']) {
  echo elgg_view($IZAP_ECOMMERCE->product . 'index', $vars);
}elseif(get_context() == 'search') {
  echo elgg_view($IZAP_ECOMMERCE->views . 'listing', $vars);
}else {
  if(elgg_view_exists('output/entity_row')) {
    
    $extra .=  '<b>' . __('price') . '</b>: <b class="color_red">'.((!isloggedin()) ? 'Not more than ' : '').'' . $vars['entity']->getPrice() . '</b><br />';
    $extra .= elgg_view($IZAP_ECOMMERCE->product . 'edit_delete', array('entity' => $vars['entity']));
    
    echo elgg_view('output/entity_row', array('entity' => $vars['entity'], 'extra' => $extra));
  }else {
    echo elgg_view($IZAP_ECOMMERCE->views . 'listing', $vars);
  }
}