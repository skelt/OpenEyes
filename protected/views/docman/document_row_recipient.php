<?php
    if(!isset($element)){
        $element = new ElementLetter();
    }

?>
<tr class="new_entry_row rowindex-<?php echo $row_index ?>" data-rowindex="<?php echo $row_index ?>">
    <td>
        <?php echo ($row_index == 0 ? 'To' : 'Cc') ?>
        <?php echo CHtml::hiddenField("DocumentTarget[" . $row_index . "][attributes][ToCc]",($row_index == 0 ? 'To' : 'Cc')); ?>
    </td>
    <td>
        
        <?php 
            $contact_type = ( isset($selected_contact_type) ? $selected_contact_type : null );
            $this->renderPartial('//docman/table/contact_name_address', array(
                'contact_id' => $contact_id,
                'contact_name' => $contact_name,
                'address_targets' => $element->address_targets,
                'is_editable' => $contact_type != 'GP',
                'contact_type' => $contact_type,
                'row_index' => $row_index,
                'address' => $address,
            ));
        
            echo CHtml::hiddenField("DocumentTarget[$row_index][attributes][contact_id]", $contact_id);
        ?>
    </td>
    <td>
        <?php $this->renderPartial('//docman/table/contact_type', array(
                                        'contact_type' => isset($selected_contact_type) ? $selected_contact_type : null,
                                        'row_index' => $row_index));
                            ?>
    </td>
    <td class="docman_delivery_method">
        <?php $this->renderPartial('//docman/table/delivery_methods', array(
                    'is_draft' => $element->draft,
                    'contact_type' => $selected_contact_type,
                    'row_index' => $row_index,
                    'can_send_electronically' => $can_send_electronically,
                ));
                ?>
        
    </td>
    <td>
        <?php if($row_index > 0): ?>
            <a class="remove_recipient removeItem" data-rowindex="<?php echo $row_index ?>">Remove</a>
        <?php endif; ?>
    </td>
</tr>