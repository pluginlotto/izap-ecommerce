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
<div>
  <div class="izap-product-description">
    <img src="<?php echo $product->getIcon('master');?>" alt="<?php $product->title?>" align="left" class="izap-product-image"/>
    <?php
    echo $product->description;
    ?>
  </div>

  <div class="clearfloat"></div>
  <p>
    <?php
    _e('tags');
    echo ': ' . elgg_view('output/tags', array('tags' => $product->tags));
    ?>
    <br />
    <?php echo elgg_view($IZAP_ECOMMERCE->product . 'edit_delete', array('entity' => $product));?>
  </p>
</div>
