<?php
/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */


global $IZAP_ECOMMERCE;

$product = $vars['entity'];
if (!$product->canEdit()) {
  return '';
}
$pro_attribs = unserialize($product->attribs);
$frm_data .= elgg_echo('izap-ecommerce:attrib_group_name');
$frm_data .= elgg_view('input/text', array('internalname' => 'attrib_group'));
$frm_data .= elgg_view('input/pulldown', array('internalname' => 'attrib_type', 'options' => array('radio', 'checkboxes')));
$frm_data .= elgg_view('input/hidden', array('internalname' => 'product_guid', 'value' => $product->guid));
$frm_data .= elgg_view('input/submit', array('value' => elgg_echo('izap-ecommerce:add_attrib_group')));
?><div class="add_group"><?php
echo elgg_view('input/form', array('body' => $frm_data, 'action' => $vars['url'] . 'action/izap_ecommerce/add_attrib_group'));
?>
</div>
<?php
$img = '<img src="' . $vars['url'] . 'mod/' . GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/_graphics/trash.gif" />';
$attrib_groups = unserialize($product->attrib_groups);
if (is_array($attrib_groups) && sizeof($attrib_groups)) {
  foreach ($attrib_groups as $key => $group) {
    $remove_link = $vars['url'] . 'action/izap_ecommerce/remove_attrib?r_type=group&g_key=' . $key . '&guid=' . $product->guid;
    $remove_html = elgg_view('output/confirmlink', array('href' => $remove_link, 'text' => $img));
?>
    <div class="add_attrib">
      <fieldset>
        <legend>
      <?php
      echo elgg_echo('izap-ecommerce:add_attrib:group') . $group['type'] . '-> ' . $group['name'] .' '. $remove_html . '</br>';
      ?></legend>
    <?php
      $attribs = $product->getAttribute($key);
      if ($attribs):
    ?>
      <?php
      echo '<b>'.elgg_echo('izap-ecommerce:add_attribute:attributes').'</b>';
       ?>
      <table  class="elgg-table-alt">
        <tbody>
        <tr>
          <th>
            Name
          </th>
          <th>
            Value
          </th>
          <th></th>
        </tr>
        <?php foreach ($attribs as $a_key => $attrib): ?>
        <tr>
          <td>
            <?php echo $attrib['name'] ?>
          </td>
          <td>
            <?php echo $attrib['value'] ?>
          </td>
          <td>
            <?php
            $remove_link = $vars['url']. 'action/izap_ecommerce/remove_attrib?r_type=attrib&key=' . $a_key . '&g_key=' . $key . '&guid=' . $product->guid;
            $remove_html = elgg_view('output/confirmlink', array('href' => $remove_link, 'text' => $img));
            echo $remove_html;
            ?>
          </td>
        </tr>

        <?php
            endforeach;
//        echo '</ul>
            ?> </tbody></table>
            <?php
        endif;
        ?>
        <label>
      <?php
            $form = '<legend>Add attribute</legend>';
            $form .= elgg_echo('izap-ecommerce:add_attrib:name');
            $form .= elgg_view('input/text', array(
            'internalname' => 'attrib_name',
            'value' => ''
            ));
      ?>
          </label>
          <label>
      <?php
            $form .= elgg_echo('izap-ecommerce:add_attrib:price');
            $form .= elgg_view('input/text', array(
            'internalname' => 'attrib_value',
            'value' => ''
            ));
            $form .= elgg_view('input/hidden', array('internalname' => 'product_guid', 'value' => $product->guid));
            $form .= elgg_view('input/hidden', array('internalname' => 'group_id', 'value' => $key));
            $form .= elgg_view('input/submit', array('internalname' => 'submit',
            'value' => elgg_echo('izap-ecommerce:add_attrib:submit')));
      ?>
          </label>
    <?php
            echo elgg_view('input/form', array(
            'body' => $form,
            'action' => $vars['url'] . 'action/izap_ecommerce/add_attrib'
            ));
    ?>
          </fieldset>
        </div><?php
          }
        }
    ?>