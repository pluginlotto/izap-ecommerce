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
  echo elgg_view($IZAP_ECOMMERCE->views.'gallery', $vars);
}elseif($vars['full']) {
  echo elgg_view($IZAP_ECOMMERCE->product.'index', $vars);
}else {
  if(elgg_view_exists('output/entity_row')) {

    if($vars['entity']->getPrice(FALSE)) {
      $extra .=  '<b>' .
              ((elgg_is_logged_in()) ? elgg_echo('izap-ecommerce:price') : elgg_echo('izap-ecommerce:price_not_more'))
              . '</b>: ' . $vars['entity']->getPrice() . '<br />';
    }else {
      $extra .=  '<b>' . elgg_echo('izap-ecommerce:free') . '</b><br />';
    }

    if(IzapEcommerce::isInWishlist($vars['entity']->guid)) {
      $extra .= '<a href="'.
              elgg_add_action_tokens_to_url(
              IzapBase::getFormAction('remove_from_wishlist',GLOBAL_IZAP_ECOMMERCE_PLUGIN).'?guid=' . $vars['entity']->guid)
              .'">'.elgg_echo('izap-ecommerce:remove_from_wishlist').'</a><br />';
    }

    $extra .= elgg_view($IZAP_ECOMMERCE->product.'edit_delete', array('entity' => $vars['entity']));
    echo elgg_view('output/entity_row', array('entity' => $vars['entity'], 'extra' => $extra));
  }else {
    echo elgg_view($IZAP_ECOMMERCE->views.'listing', $vars);
  }
}
