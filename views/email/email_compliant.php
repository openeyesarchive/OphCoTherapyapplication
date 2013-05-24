<?php
/**
 * OpenEyes
*
* (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
* (C) OpenEyes Foundation, 2011-2013
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

$exam_api = Yii::app()->moduleAPI->get('OphCiExamination');

?>

This email was generated from the OpenEyes IVT Application event
Request for AMD Injection booking sent by: <?php echo $diagnosis->user->getReportDisplay() . "\n" ?>
The Eye to inject is: <?php echo $side . "\n" ?>
Drug to use is: <?php echo $treatment->drug->name . "\n" ?>

Diagnosis: <?php echo $diagnosis->{$side . '_diagnosis'}->term  . "\n" ?>
<?php 
if ($exam_info = $exam_api->getInjectionManagementComplexInEpisodeForDisorder($patient, $event->episode, $side, $diagnosis->{$side . '_diagnosis_id'})) {
	foreach ($exam_info->{$side . '_answers'} as $answer) {
		echo $answer->question->question . ": ";
		echo ($answer->answer) ? "Yes\n" : "No\n";
	}
	echo "Comments: " . $exam_info->{$side . '_comments'} . "\n";
}
?>

Patient Details:
Full Name: <?php echo $patient->getFullName() . "\n" ?>
Number:<?php echo $patient->hos_num . "\n" ?>
NHS Number: <?php echo $patient->nhs_num . "\n" ?>
DoB: <?php echo $patient->NHSDate('dob') . "\n" ?>
Gender: <?php echo $patient->gender . "\n" ?>
Address: <?php echo ($address = $patient->contact->address) ? $address->getLetterLine() . "\n" : "Unknown\n"; ?>
PCT Code: TBD
PCT Description: TBD
PCT Address: TBD

GP Details:
Name: <?php echo ($patient->gp) ? $patient->gp->contact->fullName . "\n" : 'Unknown\n'; ?>
Address: <?php echo ($patient->practice && $patient->practice->contact->address) ? $patient->practice->contact->address->letterLine : 'Unknown'; ?>
PCT Code: TBD
PCT Description: TBD
PCT Address: TBD