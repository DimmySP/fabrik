<?php
/**
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005 Fabrik. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/**
 * this is an example plugin validation rule.
 * To create a new validation rule from this example
 * 1) Copy the folder 'example' and the three files it containts (example.php, example.xml, index.html)
 * 2) Rename the folder and the php and xml file to the name of your plugin
 * e.g.
 * isurl, isurl.php and isurl.xml
 * 3) Edit isurl.xml and change the data to match your details, theer are 2 essential lines to change:
 *
 * a) <name>Example</name>
 * b) <filename plugin="example">example.php</filename>
 *
 * for these two lines replace 'example' with the name of your plugin, e.g.
 * a) <name>IsUrl</name>
 * b) <filename plugin="isurl">isurl.php</filename>
 *
 * 4) In the php file (e.g. isurl.php) , edit the lines:
 *
 * class FabrikModelIsexample extends FabrikModelValidationRule {
 * protected $pluginName = 'example';
 *
 * replacing 'example' with your plugin's name.
 *
 * 5) Now to the heart of the matter - the validation itself. This takes place inside the validate() function
 * 2 variables are passed to this function:
 *
 * i) $data - the data entered in the form
 * ii) $element - the element model that the validation rule has been attached to
 *
 * You will generally only need to run your test against the $data variable.
 *
 * The validate() function should return true or false. True for when the data meets the rule's criteria
 * False for when it fails. For our 'isurl' example a fail would occur if the person had not entered a url
 * Alter the validation function to suit your own needs.
 *
 * 6) Installation - make a zip file of your validation rule's folder (e.g. 'isurl')
 * Go to your site's administration panel and select components->fabrik->plugins
 * press the install button
 * from the file upload field, browse to find your zip file.
 * Press the upload button
 *
 *
 */
// Check to ensure this file is included in Joomla!

defined('_JEXEC') or die();

// Require the abstract plugin class
require_once COM_FABRIK_FRONTEND . '/models/validation_rule.php';

class PlgFabrik_ValidationruleExample extends PlgFabrik_Validationrule
{

	protected $pluginName = 'example';

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
		$found = preg_match("/http:/i", $data);
		return $found;
	}

	/**
	 * Checks if the validation should replace the submitted element data
	 * if so then the replaced data is returned otherwise original data returned
	 *
	 * @param   string  $data           original data
	 * @param   model   &$elementModel  element model
	 * @param   int     $pluginc        validation plugin counter
	 * @param   int     $repeatCounter  repeat group counter
	 *
	 * @return  string	original or replaced data
	 */

	public function replace($data, &$elementModel, $pluginc, $repeatCounter)
	{
		return $data;
	}
}
