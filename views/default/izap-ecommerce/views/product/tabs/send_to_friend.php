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

global $IZAP_ECOMMERCE, $CONFIG;
$product = $vars['entity'];
?>

<div class="izapcontentWrapper">

  <form action="<?php echo $vars['url']?>action/izap_ecommerce/sendtofriend" method="post">
    <?php
    echo elgg_view('input/securitytoken');
    echo $vars['guid']?elgg_view('input/hidden', array('name' => 'attributes[guid]', 'value' => $product->guid)):""; ?>

    <p>
      <label for="name" ><?php echo elgg_echo('izap-ecommerce:your_name');?></label>
      <?php echo elgg_view('input/text', array('name' => 'attributes[_name]', 'value' => $vars['postArray']['name'], 'id'=>"name")) ;?>
    </p>

    <p>
      <label for="email" ><?php echo elgg_echo('izap-ecommerce:your_email');?></label>
      <?php echo elgg_view('input/text', array('name' => 'attributes[_email]', 'value' => $vars['postArray']['email'], 'id'=>"email")) ;?>
    </p>

    <p>
      <label for="send_name" ><?php echo elgg_echo('izap-ecommerce:your_friend_name');?></label>
      <?php echo elgg_view('input/text', array('name' => 'attributes[_send_name]', 'value' => $vars['postArray']['send_name'], 'id'=>"send_name")) ;?>
    </p>

    <p>
      <label for="send_email" ><?php echo elgg_echo('izap-ecommerce:your_friend_email');?></label>
      <?php echo elgg_view('input/text', array('name' => 'attributes[_send_email]', 'value' => $vars['postArray']['send_email'], 'id'=>"send_email")) ;?>
    </p>

    <p>
      <label for="msg"><?php echo elgg_echo('izap-ecommerce:message');?></label>
      <?php echo elgg_view('input/plaintext', array('name' => 'attributes[_msg]', 'value' => $vars['postArray']['msg'], 'id'=>"msg"));?>
    </p>

    <p>
      <?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('submit'))); ?>
    </p>
  </form>

</div>
<?php unset ($_SESSION['postArray']);?>