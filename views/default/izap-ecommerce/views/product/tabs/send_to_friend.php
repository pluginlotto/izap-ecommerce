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

<div class="contentWrapper">

  <form action="<?php echo $vars['url']?>action/izap_ecommerce/sendtofriend" method="post">
    <?php
    echo elgg_view('input/securitytoken');
    echo $vars['guid']?elgg_view('input/hidden', array('internalname' => 'attributes[guid]', 'value' => $product->guid)):"";
    ?>

    <p>
      <label for="name" ><?php _e('your_name');?></label>
      <?php echo elgg_view('input/text', array('internalname' => 'attributes[_name]', 'value' => $vars['postArray']['name'], 'internalid'=>"name")) ;?>
    </p>

    <p>
      <label for="email" ><?php _e('your_email');?></label>
      <?php echo elgg_view('input/text', array('internalname' => 'attributes[_email]', 'value' => $vars['postArray']['email'], 'internalid'=>"email")) ;?>
    </p>

    <p>
      <label for="send_name" ><?php _e('your_friend_name');?></label>
      <?php echo elgg_view('input/text', array('internalname' => 'attributes[_send_name]', 'value' => $vars['postArray']['send_name'], 'internalid'=>"send_name")) ;?>
    </p>

    <p>
      <label for="send_email" ><?php _e('your_friend_email');?></label>
      <?php echo elgg_view('input/text', array('internalname' => 'attributes[_send_email]', 'value' => $vars['postArray']['send_email'], 'internalid'=>"send_email")) ;?>
    </p>

    <p>
      <label for="msg"><?php _e('message');?></label
      <?php echo elgg_view('input/longtext', array('internalname' => 'attributes[_msg]', 'value' => $vars['postArray']['msg'], 'internalid'=>"msg"));?>
    </p>

    <p>
      <?php echo elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('submit'))); ?>
    </p>
  </form>

</div>
<?php unset ($_SESSION['postArray']);?>