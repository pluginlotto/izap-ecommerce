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
    var extra = product_price;
    var selected_options = '';
    
    $("input[type='radio']:checked").each(function () {
      extra += parseInt($(this).val());
    });

    $("input[type='checkbox']:checked").each(function () {
      extra += parseInt($(this).val());
      selected_options += $(this).html() + '|';
    });
    
    $('#product_price_html').html('$' + extra);
    $('input[name="product_attribs[selected_options]"]').val(selected_options);
  }
</script>
<?php
if(sizeof($vars['attribs'])) {
  foreach($vars['attribs'] as $key => $attrib) {
    if($attrib['description'] != '') {
      $tooltip = '<a href="#" rel="tooltip" title="'.$attrib['description'].'">?</a>';
    }
    if($vars['entity']->canEdit()) {
      $remove_link = func_get_actions_path_byizap(array(
        'plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN,
      )) . 'remove_attrib?r_type=attrib&key=' . $key . '&g_key=' . $vars['group_id'] . '&guid=' . $vars['entity']->guid;
      
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