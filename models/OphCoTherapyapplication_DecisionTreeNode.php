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

/**
 * This is the model class for table "ophcotherapya_decisiontreenode".
 * 
 * Each node is a question for a given assessment flow. If it's parent is null, it is the root node
 * for the related flow. There should only be one with a null parent for each flow.
 *
 * @property integer $id The node id
 * @property integer $decisiontree_id The id of the assessment flow that this node is for
 * @property integer $parent_id The id of the node's parent (if it has one)
 * @property string $question The question that this node is asking, if relevant
 * @property integer $outcome_id If this is an outcome node, the id of the outcome it represents
 * @property string $default_function The name of the function that should be used to determine the default response to this node
 * @property string $default_value The default value that should be set for this node (if no default function selected)
 * @property string $response_type The response type for this node (va - value, ch - choice)
 * 
 * @property OphCoTherapyapplication_DecisionTree $decisiontree
 * @property OphCoTherapyapplication_DecisionTreeNode $parent
 * @property OphCoTherapyapplication_DecisionTreeOutcome $outcome
 **/
 
class OphCoTherapyapplication_DecisionTreeNode extends BaseActiveRecord {
	/**
	 * Returns the static model of the specified AR class.
	 * @return the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ophcotherapya_decisiontreenode';
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'decisiontree' => array(self::BELONGS_TO, 'OphCoTherapyapplication_DecisionTree', 'decisiontree_id'),
			'parent' => array(self::BELONGS_TO, 'OphCoTherapyapplication_DecisionTreeNode', 'parent_id'),
			'outcome' => array(self::BELONGS_TO, 'OphCoTherapyapplication_DecisionTreeOutcome', 'outcome_id'),
			'rules' => array(self::HAS_MANY, 'OphCoTherapyapplication_DecisionTreeNodeRule', 'node_id'),
			'response_type' => array(self::BELONGS_TO, 'OphCoTherapyapplication_DecisionTreeNode_ResponseType', 'response_type_id'),
			'children' => array(self::HAS_MANY, 'OphCoTherapyapplication_DecisionTreeNode', 'parent_id'),
		);
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
				array('question, outcome_id, default_function, default_value, response_type_id', 'safe'),
				array('outcome', 'outcomeValidation'),
				array('question, response_type_id', 'requiredIfNotOutcomeValidation'),
				array('default_function', 'defaultsValidation'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, question, outcome_id, default_function, default_value, response_type_id', 'safe', 'on' => 'search'),
		);
	}
	
	public function getNextNode($val)
	{
		// get the nodes that have this node as a parent
		
		// for each of those nodes, check the rules to see if they apply
		// and return it if they do
	}
	
	/*
	* outcome being set implies that no other attributes should be set for the node
	*/
	public function outcomeValidation($attribute) {
		if ($this->outcome_id && ($this->question || $this->default_function || $this->default_value || $this->response_type)) {
			$this->addError($attribute, 'Outcome nodes cannot have any other values set.');
		}
	}
	
	/*
	 * if outcome is null then it implies this node should be a question node
	 */
	public function requiredIfNotOutcomeValidation($attribute) {
		if (!$this->outcome_id && !$this->$attribute) {
			$this->addError($attribute, $this->getAttributeLabel($attribute) . ' required if not an outcome node.');
		}
	}
	
	/*
	 * can only have one source for the default response for the node
	 */
	public function defaultsValidation($attribute) {
		if ($this->default_function && $this->default_value) {
			$this->addError($attribute, 'Cannot have two default values for node response');
		}
	}
}