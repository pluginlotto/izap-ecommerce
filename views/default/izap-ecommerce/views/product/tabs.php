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
echo izap_elgg_bridge_view('tabs',array(
'tabsArray'=>array(
        array(
                'title'=>elggb_echo('comments'),
                'content'=>elgg_view($IZAP_ECOMMERCE->product . 'tabs/comments',array('entity'=>$product)),
        ),
        array(
                'title'=>elggb_echo('send_to_friend'),
                'content'=>elgg_view($IZAP_ECOMMERCE->product . 'tabs/send_to_friend',array('entity'=>$product)),
        ),
        array(
                'title'=>elggb_echo('terms'),
                'content'=>elgg_view($IZAP_ECOMMERCE->product . 'tabs/terms',array('entity'=>$product)),
        ),
)
));
?>
<script type="text/javascript">
    $(document).ready(function(){
      var anchor=$(location).attr('hash');
      var anchor2=$(location).attr('hash').substring(1);
      if(anchor.search('comment_')==1) {
        $.tabsByIzap('elgg_horizontal_tabbed_nav', 'tabs-0');
      }
    });
</script>