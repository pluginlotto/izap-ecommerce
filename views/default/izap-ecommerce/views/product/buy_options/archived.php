<?php
/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */
$product = elgg_extract('entity', $vars);
$new_version = get_product_izap_ecommerce($product->parent_guid);
if ($new_version) {
?>
  <div class="download">
    <a href="<?php echo $new_version->getURL(); ?>" class="img">
      <img src="<?php echo $vars['url'] . 'mod/' . GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/_graphics/latest.png'; ?>" />
    </a>
    <a href="<?php echo $new_version->getURL(); ?>" class="text"><?php echo
  elgg_echo('izap-ecommerce:get_latest_version') ?></a>
</div>
<?php
}