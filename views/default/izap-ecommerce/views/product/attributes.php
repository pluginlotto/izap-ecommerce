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
?>
<script type="text/javascript">
  function update_price_field() {
    var extra = parseInt(<?php echo $vars['entity']->getPrice(FALSE);?>);

    $("input[type='radio']:checked").each(function () {
      if($(this).val() != '' && $(this).attr('name').search('product_attribs') >= 0) {
        extra += parseInt($(this).val());
      }
    });


    $("input[type='checkbox']:checked").each(function () {
      if($(this).val() != '' && $(this).attr('name').search('product_attribs') >= 0) {
        extra += parseInt($(this).val());
      }
    });

    $('#product_price_html').html('$' + extra);
  }
</script>
<?php
if(sizeof($vars['attribs'])) {
  foreach($vars['attribs'] as $key => $attrib) {
    if($attrib['description'] != '') {
      $tooltip = '<a href="#" rel="tooltip" title="'.$attrib['description'].'">?</a>';
    }
    if($vars['entity']->canEdit()) {
      $remove_link = $vars['url']. 'action/izap_ecommerce/remove_attrib?r_type=attrib&key=' . $key . '&g_key=' . $vars['group_id'] . '&guid=' . $vars['entity']->guid;
      
      $remove_html = elgg_view('output/confirmlink', array('href' => $remove_link, 'text' => ' X '));
    }
    $options[$attrib['name'] . '('.$IZAP_ECOMMERCE->currency_sign.$attrib['value'].') ' . $tooltip . $remove_html] = $attrib['value'] . '|' . $key;
  }

  if($vars['type'] == 'radio') {
    $options['none'] = 0;
  }
  
  echo elgg_view('input/' . $vars['type'], array(
  'internalname' => 'product_attribs['.$vars['group']['name'] . "|" .$vars['type'].']',
  'options' => $options,
    'js' => 'onclick="update_price_field();"'
  )
  );
}