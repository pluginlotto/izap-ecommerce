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
if ($product->canDownload()) {
  $donwload_link = create_product_download_link_izap_ecommerce(rand(0, 1000), $product->guid);
?>
  <div class="download">
    <a href="<?php echo $donwload_link ?>" class="img">
      <img src="<?php echo $vars['url'] . 'mod/' . GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/_graphics/download.png' ?>" />
    </a>

    <a href="<?php echo $donwload_link ?>" class="text">
    <?php echo elgg_echo('izap-ecommerce:download'); ?>
    <span class="download_desc"><?php echo '(' . IzapBase::byteToMb($product->size()) . ' Mb)' ?></span>
  </a>
  <div class="clearfloat"></div>
</div>

<?php
  }