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

global $IZAP_ECOMMERCE, $IZAPTEMPLATE;
$product = $vars['entity'];
echo elgg_view_title($product->title);
?>
<div class="contentWrapper">
<?php
echo $IZAPTEMPLATE->render('product/info', $vars);
echo $IZAPTEMPLATE->render('product/tabs', $vars);
?>
</div>