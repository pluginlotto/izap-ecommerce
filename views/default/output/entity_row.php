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

$entity = $vars['entity'];
if(!$entity) {
  return '';
}
$owner = $entity->getOwnerEntity();
?>
<div class="contentWrapper">
  
  <?php if($vars['remove_icon'] !== TRUE) {?>
  <div class="listing-image">
    <a href="<?php echo $entity->getUrl()?>">
      <img src="<?php echo $entity->getIcon();?>" alt="<?php echo $entity->title?>" align="left" width="100" height="100"/>
    </a>
  </div>
    <?php } ?>
  
  <div class="listing-description">
    <h3>
      <a href="<?php echo $entity->getUrl();?>">
        <?php echo $entity->title;?>
      </a>
    </h3>
    <p align="justify">
      <?php
      echo substr(filter_var($entity->description, FILTER_SANITIZE_STRING), 0, 200);
      ?>
    </p>
  </div>

  <div class="listing-options">
    <?php
    echo elgg_view('output/rate', array('entity' => $entity));
    $total_views = func_get_views_byizap($entity);
    $total_comments = elgg_count_comments($entity);
    if($total_views) {
      ?>
    <b>#</b> <?php echo $total_views?><br />
      <?php
    }
    ?>
    <b>
      By:
    </b>
    <a href="<?php echo $owner->getURL();?>">
      <?php
      echo $owner->name;
      ?>
    </a>
    <br />
    <?php if($total_comments) {?>
    <b>
      Comments(<?php echo $total_comments;?>)
    </b>
    <br />
      <?php }?>
    <?php
    echo $vars['extra'];
    ?>
  </div>

  <div class="clearfloat"></div>
  <p>
    <?php
    //echo '<img src="'.func_get_www_path_byizap(array('type' => 'images', 'plugin' => 'izap-skin')).'tag.gif" alt="Tags"/>';
    echo '<img src="'.$vars['url'].'_graphics/icon_tag.gif" alt="Tags"/>';
    echo elgg_view('output/tags', array('tags' => $entity->tags));
    ?>
  </p>
</div>

