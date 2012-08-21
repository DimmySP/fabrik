<?php
/**
 * @package     Joomla
 * @subpackage  Fabrik
 * @author      Lieven Gryp <dont@know.com>
 * @copyright   Copyright (C) 2005 Fabrik & Lieven Gryp. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the abstract plugin class
require_once COM_FABRIK_FRONTEND . '/models/validation_rule.php';

class PlgFabrik_ValidationruleAreUniqueValues extends PlgFabrik_Validationrule
{

	protected $pluginName = 'areuniquevalues';

	/**
	 * If true uses icon of same name as validation, otherwise uses png icon specified by $icon
	 *
	 *  @var bool
	 */
	protected $icon = 'notempty';

	/**
	 * Validate the elements data against the rule
	 *
	 * @param   string  $data           to check
	 * @param   object  &$elementModel  element Model
	 * @param   int     $pluginc        plugin sequence ref
	 * @param   int     $repeatCounter  repeat group counter
	 *
	 * @return  bool  true if validation passes, false if fails
	 */

	public function validate($data, &$elementModel, $pluginc, $repeatCounter)
	{
		// Could be a dropdown with multivalues
		if (is_array($data))
		{
			$data = implode('', $data);
		}
		$params = $this->getParams();
		$otherfield = (array) $params->get('areuniquevalues-otherfield', array());
		$otherfield = $otherfield[$pluginc];
		$element = $elementModel->getElement();
		$listModel = $elementModel->getlistModel();
		$table = $listModel->getTable();
		if ((int) $otherfield !== 0)
		{
			$otherElementModel = $this->getOtherElement($elementModel, $pluginc);
			$otherFullName = $otherElementModel->getFullName(false, true, false);
			$otherfield = $otherElementModel->getFullName(false, false, false);
		}
		else
		{
			// Old fabrik 2.x params stored element name as a string
			$otherFullName = $table->db_table_name . '___' . $otherfield;
		}

		$db = $listModel->getDb();
		$lookuptable = $db->quoteName($table->db_table_name);
		$data = $db->quote($data);

		$query = $db->getQuery(true);
		$query->select('COUNT(*)')->from($lookuptable)->where($elementModel->getFullName(false, false, false) . ' = ' . $data);

		$listModel->buildQueryJoin($query);

		if (!empty($otherfield))
		{
			// $$$ the array thing needs fixing, for now just grab 0
			$formdata = $elementModel->getForm()->formData;
			$v = JArrayHelper::getValue($formdata, $otherFullName . '_raw', JArrayHelper::getValue($formdata, $otherFullName, ''));
			if (is_array($v))
			{
				$v = JArrayHelper::getValue($v, 0, '');
			}
			$query->where($otherfield . ' = ' . $db->quote($v));
		}

		/* $$$ hugh - need to check to see if we're editing a record, otherwise
		 * will fail 'cos it finds the original record (assuming this element hasn't changed)
		 * @TODO - is there a better way getting the rowid?  What if this is form a joined table?
		 */
		$rowid = JRequest::getVar('rowid');
		if (!empty($rowid))
		{
			$query->where($table->db_primary_key . ' != ' . $db->quote($rowid));
		}
		$db->setQuery($query);
		$c = $db->loadResult();
		return ($c == 0) ? true : false;
	}

	/**
	 * Gets the other element model to compare this plugins element data against
	 *
	 * @param   object  $elementModel  element model
	 * @param   int     $pluginc       plugin render order
	 *
	 * @return	object element model
	 */

	private function getOtherElement($elementModel, $pluginc)
	{
		$params = $this->getParams();
		$otherfield = (array) $params->get('areuniquevalues-otherfield');
		$otherfield = $otherfield[$pluginc];
		return FabrikWorker::getPluginManager()->getElementPlugin($otherfield);
	}

	/**
	 * Gets the hover/alt text that appears over the validation rule icon in the form
	 *
	 * @param   object  $elementModel  element model
	 * @param   int     $pluginc       plugin render order
	 *
	 * @return	string	label
	 */

	protected function getLabel($elementModel, $pluginc)
	{
		$otherElementModel = $this->getOtherElement($elementModel, $pluginc);
		$params = $this->getParams();
		$otherfield = (array) $params->get('areuniquevalues-otherfield');
		$otherfield = $otherfield[$pluginc];
		if ((int) $otherfield !== 0)
		{
			return JText::sprintf('PLG_VALIDATIONRULE_AREUNIQUEVALUES_ADDITIONAL_LABEL', $otherElementModel->getElement()->label);
		}
		else
		{
			return parent::getLabel($elementModel, $pluginc);
		}
	}
}
