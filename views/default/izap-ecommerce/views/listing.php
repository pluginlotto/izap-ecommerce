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
global $IZAP_ECOMMERCE;
$product = $vars['entity'];
?>
<div class="contentWrapper">
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
      ?>
    </p>
  </div>

  <div class="listing-options">
    <?php if($product->getPrice(FALSE)) echo __('price') . ': ' . $product->getPrice() . '<br />'; else echo __('free') . '<br />';?>
    <?php echo elgg_view('output/rate', array('entity' => $product));?>
    <?php echo elgg_view($IZAP_ECOMMERCE->product . 'edit_delete', array('entity' => $product));?>
  </div>
  
  <div class="clearfloat"></div>
    <p>
      <?php
      _e('tags');
      echo ': ' . elgg_view('output/tags', array('tags' => $product->tags));
      ?>
    </p>
  </div>
