<?php
/**
 * OpenEyes.
 *
 * (C) OpenEyes Foundation, 2016
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.openeyes.org.uk
 *
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2016, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>

<?php $this->renderPartial('//base/_messages'); ?>

<div class="box admin">
    <header class="box-header">
        <h2 class="box-title"><?php echo $title ? $title : 'PASAPI Admin' ?></h2>
        <div class="box-actions">
            <a class="button small warning" href="<?php echo Yii::app()->createUrl($this->module->getName().'/admin/viewXpathRemaps'); ?>">Back to Index</a>
            <a class="button small" href="<?php echo Yii::app()->createUrl($this->module->getName().'/admin/create'.$model_class, array('id' => $remap->id)); ?>">Add New Value</a>
        </div>
    </header>

    <table class="grid">
        <thead>
        <tr>
            <th>Input</th>
            <th>Output</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($model_list as $i => $model) {
            ?>
            <tr data-attr-id="<?php echo $model->id?>">
                <td>
                    <a href="<?php echo Yii::app()->createUrl($this->module->getName().'/admin/update'.Helper::getNSShortname($model), array('id' => $model->id)) ?>"><?php echo $model->input ?></a>
                </td>
                <td>
                    <?= $model->output ?>
                </td>
                <td>
                    <a href="<?php echo Yii::app()->createUrl($this->module->getName().'/admin/deleteRemapValue', array('id' => $model->id)) ?>">Delete</a>
                </td>
            </tr>
            <?php

        }?>
        </tbody>
    </table>
</div>
