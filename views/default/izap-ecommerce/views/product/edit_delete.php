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
$product = $vars['entity'];
if($product->canEdit() && !$product->isArchived()) {
  ?>
<a href="<?php echo $IZAP_ECOMMERCE->link?>edit/<?php echo $product->guid?>">
    <?php echo elgg_echo('izap-ecommerce:edit');?>
</a>
/
  <?php
  echo elgg_view('output/confirmlink', array(
  'text' => elgg_echo('izap-ecommerce:delete'),
  'href' => elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/delete?guid=' . $product->guid),
  ));

}elseif($product->canEdit()) {
  echo elgg_view('output/confirmlink', array(
  'text' => elgg_echo('izap-ecommerce:delete'),
  'href' => elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/delete?guid=' . $product->guid),
  ));
}
