<?php
/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
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
?>
<div class="izap-product-info">
  <div><div class="left">
      <?php
      echo elgg_view($IZAP_ECOMMERCE->product . 'buy', array('entity' => $product));
      ?>
      <div class="izap-product-extra"><?php
      echo elgg_view('likes/display', array('entity' => $product));
      ?>
        <span class="wishlist">
          <?php
          echo elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/buy_options/wishlist', array('entity' => $product));
          ?>
        </span>
      </div>

      <?php
          echo elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/view_attributes', array('entity' => $product));
      ?>
        </div>

        <div class="right">
          <img src="<?php echo $product->getIcon('master'); ?>" alt="<?php $product->title ?>" class="izap-product-image" />
          <div style="text-align:right">
            <a href="<?php echo $product->getIcon('orignal'); ?>">
          <?php echo elgg_echo('izap-ecommmerce:get_original') ?>
        </a>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="description">
    <?php echo elgg_view('output/longtext', array('value' => $product->description)); ?>
          <p align="right">
      <?php
          echo elgg_view('output/tags', array('tags' => $product->tags));
      ?>
      <br />
    </p>
  </div>
</div>

