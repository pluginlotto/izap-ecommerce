<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

global $IZAP_ECOMMERCE;

$number = (int) $vars['entity']->num_display;
if(!$number) {
  $number = 4;
}
$options = array(
        'type' => 'object',
        'subtype'=> $IZAP_ECOMMERCE->object_name,
        'limit' => $number,
);

if(is_callable('elgg_get_entities')) {
  $products = elgg_get_entities($options);
}else {
  extract($options);
  $products = get_entities($type, $subtype, 0, '', $limit);
}

$size = $vars['entity']->thumb;
if(!$size) {
  $size = 'small';
}
if($size == 'small') {
  $sub_length = 4;
}else if($size == 'medium') {
  $sub_length = 12;
}
?>
<div class="contentWrapper">
  <?php
  foreach($products as $product) {
    $icon = $product->getIconUrl($size);

    ?>
  <div class="izap_ecommerce_widget_view">
    <a href="<?php echo $product->getURL();?>" title="<?php echo $product->title?>"><img src="<?php echo $icon; ?>">
      <br />
        <?php echo substr($product->title,0,$sub_length).".."; ?>
    </a>
  </div>
    <?php
  }
  ?>
  <div class="clearfloat"></div>
</div>