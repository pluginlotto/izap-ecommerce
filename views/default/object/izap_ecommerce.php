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
if(get_input('search_viewtype') == 'gallery') {
  echo $IZAP_ECOMMERCE->product . 'gallery';
  echo elgg_view($IZAP_ECOMMERCE->product . 'gallery', $vars);
}elseif($vars['full']) {
  echo elgg_view($IZAP_ECOMMERCE->product . 'index', $vars);
}else {
  if(elgg_view_exists('output/entity_row')) {

    if($vars['entity']->getPrice(FALSE)) {
    $extra .=  '<b>' . __('price') . '</b>: ' . $vars['entity']->getPrice() . '<br />';
    }else{
      $extra .=  '<b>' . __('free') . '</b><br />';
    }
    
    $extra .= elgg_view($IZAP_ECOMMERCE->product . 'edit_delete', array('entity' => $vars['entity']));
    echo elgg_view('output/entity_row', array('entity' => $vars['entity'], 'extra' => $extra));
  }else {
    echo elgg_view($IZAP_ECOMMERCE->views . 'listing', $vars);
  }
}