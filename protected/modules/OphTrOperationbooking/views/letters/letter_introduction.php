<?php
/**
 * OpenEyes.
*
* (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
* (C) OpenEyes Foundation, 2011-2013
* This file is part of OpenEyes.
* OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
*
* @link http://www.openeyes.org.uk
*
* @author OpenEyes <info@openeyes.org.uk>
* @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
* @copyright Copyright (c) 2011-2013, OpenEyes Foundation
* @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
*/
?>
<p<?php if (@$accessible) {?> class="accessible"<?php }?>>Dear <?php echo $to; ?>,</p>
<p<?php if (@$accessible) {?> class="accessible"<?php }?>>
	<?php if (@$patient_ref) {
    echo $patient->fullname.', ';
}?>
	<strong>Hospital Reference Number: <?php echo $patient->hos_num; ?>
	<?php if ($patient->nhsnum) {?><br/> NHS Number: <?php echo $patient->nhsnum; }?>
	<?php if (@$patient_ref) {?>
	<br /><?php echo $patient->getLetterAddress(array('delimiter' => ', '))?>
	<br />DOB: <?php echo $patient->NHSDate('dob') ?>, <?php echo ($patient->gender == 'M') ? 'Male' : 'Female'; ?>
	<?php }?></strong>
</p>
