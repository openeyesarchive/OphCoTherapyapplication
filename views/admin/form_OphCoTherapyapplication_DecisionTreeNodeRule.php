<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>

<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<div class="row outcome parent_check">
	<?php echo $form->labelEx($model,'parent_check'); ?>
	<?php echo $form->dropdownlist($model,'parent_check',$model->COMPARATORS,array('empty'=>'- Please select -')); ?>
	<?php echo $form->error($model,'parent_check'); ?>
</div>

<div class="row parent_check_value">
	<?php echo $form->labelEx($model,'parent_check_value'); ?>
	<?php echo $form->textField($model,'parent_check_value',array('size'=>60,'maxlength'=>16)); ?>
	<?php echo $form->error($model,'parent_check_value'); ?>
</div>
