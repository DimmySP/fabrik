<?php
/**
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005 Fabrik. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the abstract plugin class
require_once COM_FABRIK_FRONTEND . '/models/validation_rule.php';

class PlgFabrik_ValidationruleNotempty extends PlgFabrik_Validationrule
{

	protected $pluginName = 'notempty';

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
		$ok = $elementModel->dataConsideredEmpty($data, $repeatCounter);
		return !$ok;
	}

}
