<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version {version} $Revision: {revision}
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */
elgg_load_js('lightbox');
elgg_load_css('lightbox'); ?>
<script>
  $(function() {
    $('#gallery .light').fancybox({
    })
  });
</script>
<?php
global $IZAP_ECOMMERCE;
$product = $vars['entity'];

// display form only if user can edit it
if($product->canEdit()) {
  echo elgg_view($IZAP_ECOMMERCE->forms.'add_screenshots', array('entity' => $product));
}

$screenshots = unserialize($product->screenshots);
if(sizeof($screenshots)) {
  foreach($screenshots as $thumb) {
    $image_url = $vars['url'].'pg/store/screenshots/'. $product->guid . '/' .$thumb;
    $thumb_url = $vars['url'].'pg/store/screenshots/'. $product->guid . '/thumb_' .$thumb;
    //$delete_url = $vars['url'].'pg/store/delete_screenshot/'. $product->guid . '/' .$thumb;
    $delete_url = "{$vars['url']}action/izap_ecommerce/delete_screenshot?product_guid={$product->guid}&thumb={$thumb}";
    ?>
<div id="gallery" class="screeshot_thumb">
  <a
    href="<?php echo $image_url?>"
    class="light"><img
      src="<?php echo $thumb_url?>"
      alt="<?php echo $product->title . '_'. $thumb?>"/></a>
      <?php if($product->canEdit()) {?>
  <br />

        <?php
        echo elgg_view('output/confirmlink', array(
        'text' => elgg_echo('izap-ecommerce:delete'),
        'href' => $delete_url,
        ));

      }?>
</div>
    <?php
  }
}
?>
<div class="clearfloat"></div>