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
GLOBAL $IZAP_ECOMMERCE;
$product = $vars['entity'];
if(isadminloggedin()) {
  ?>
<a href="<?php echo $IZAP_ECOMMERCE->link?>edit/<?php echo $product->guid?>">
    <?php echo __('edit');?>
</a>
/
  <?php
  echo elgg_view('output/confirmlink', array(
  'text' => __('delete'),
  'href' => elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/delete?guid=' . $product->guid),
  ));

}
