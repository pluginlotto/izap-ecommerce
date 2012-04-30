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
if(!$product->canEdit()) {
  return '';
}
$pro_attribs = unserialize($product->attribs); ?>
<span class="add_attrib_button" onclick="javascript:$('#izap_product_attrib_group_form').toggle();">Add Group</span>
<div class="clearfloat"></div>
<div id="izap_product_attrib_group_form">
  <?php
  $frm_data .= elgg_echo('izap-ecommerce:attrib_group_name');
  $frm_data .= elgg_view('input/text', array('name' => 'attrib_group'));
  $frm_data .= elgg_view('input/dropdown', array('name' => 'attrib_type', 'options' => array('radio', 'checkboxes')));
  $frm_data .= elgg_view('input/hidden', array('name' => 'product_guid', 'value' => $product->guid));
  $frm_data .= elgg_view('input/submit', array('value' => elgg_echo('izap-ecommerce:add_attrib_group')));

  echo elgg_view('input/form', array('body' => $frm_data, 'action' => $vars['url'] . 'action/izap_ecommerce/add_attrib_group'));
  ?>
</div>

<?php
$attrib_groups = unserialize($product->attrib_groups);
if(is_array($attrib_groups) && sizeof($attrib_groups)) {
  foreach($attrib_groups as $key => $group) {
      $remove_link = $vars['url'].'action/izap_ecommerce/remove_attrib?r_type=group&g_key=' . $key . '&guid=' . $product->guid;

      $remove_html = elgg_view('output/confirmlink', array('href' => $remove_link, 'text' => ' X '));
    ?>
<fieldset>
  <legend><?php echo $group['name'] . $remove_html?></legend>
  <span class="add_attrib_button" onclick="javascript:$('#izap-product_attrib_form_<?php echo $key?>').toggle();">Add Attribute</span>
  <div class="clearfloat"></div>
  <div id="izap-product_attrib_form_<?php echo $key?>" class="izap-product_attrib_form">
        <?php ob_start();?>
    <table>
      <tr>
        <td>Name</td>
        <td>Amount</td>
      </tr>
      <tr>
        <td><input type="text" name="attrib_name" size="12" /></td>
        <td><input type="text" name="attrib_value" size="12" /></td>
      </tr>
      <tr>
        <td colspan="2">
        Description<br />
        <textarea cols="25" rows="3" name="attrib_help"></textarea>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <input type="hidden" name="product_guid" value="<?php echo $product->guid?>" />
          <input type="hidden" name="group_id" value="<?php echo $key?>" />
          <input type="submit" value="Add Attribute" />
        </td>
      </tr>
    </table>
        <?php
        $form_body = ob_get_clean();
        $action =  $vars['url']. 'action/izap_ecommerce/add_attrib';
        echo elgg_view('input/form', array(
          'body' => $form_body,
          'id' => 'attrib_add_form_' . $key,
          'action' => $action
          ) );
        ?>
  </div>
  <div class="clearfloat"></div>
  <div id="izap-product_attrib_<?php echo $key?>"><?php echo elgg_view($IZAP_ECOMMERCE->product .'attributes', array(
    'type' => $group['type'], 'attribs' => $pro_attribs[$key], 'entity' => $product, 'group_id' => $key));?></div>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#<?php echo 'attrib_add_form_'.$key?>').submit(function(){
        var action = '<?php echo $action;?>';
              $.ajax({
                type: 'POST',
                url: action,
                data: $('#<?php echo 'attrib_add_form_'.$key?>').serialize(),
                success: function(data){
                  $('#izap-product_attrib_<?php echo $key?>').html(data);
                  $('input[name="attrib_name"]').val('');
                  $('input[name="attrib_value"]').val('');
                }
              });

              return false;
            });
          });
  </script>
</fieldset>
    <?php
  }
}
?>
<br />