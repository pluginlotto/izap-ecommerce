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
?>
<div class="izapcontentWrapper">
  <div class="listing-image">
    <a href="<?php echo $product->getUrl()?>">
      <img src="<?php echo $product->getIcon();?>" alt="<?php echo $product->title?>" align="left"/>
    </a>
  </div>

  <div class="listing-description">
    <h3>
      <a href="<?php echo $product->getUrl();?>">
        <?php echo $product->title;?>
      </a>
    </h3>
    <p align="justify">
      <?php
      echo substr(filter_var($product->description, FILTER_SANITIZE_STRING), 0, 200);
      echo '<br />';
      echo elgg_view('output/tags', array('tags' => $product->tags));
      ?>
    </p>
  </div>

  <div class="listing-options">
    <?php if($product->getPrice(FALSE)) echo elgg_echo('izap-ecommerce:price') . ': ' . $product->getPrice() . '<br />'; else echo elgg_echo('izap-ecommerce:free') . '<br />';?>
    <?php echo elgg_view('output/rate', array('entity' => $product));?>
    <?php echo elgg_view($IZAP_ECOMMERCE->product . 'edit_delete', array('entity' => $product));?>
  </div>
  
  <div class="clearfloat"></div>
    <p>
      <?php
      
      ?>
    </p>
  </div>
