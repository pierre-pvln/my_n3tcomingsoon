<?php
/**
 * @package n3tComingSoon
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2013 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

JFormHelper::loadFieldClass('textarea');

class JFormFieldIPList extends JFormFieldTextarea
{

	protected $type = 'IPList';

	protected function getInput()
	{
    $button = '<br />';
    $onclick = '$(\'jform_params_ip_filter\').set(\'value\',$(\'jform_params_ip_filter\').get(\'value\')+($(\'jform_params_ip_filter\').get(\'value\') ? \'\n\' : \'\')+\''.$_SERVER['REMOTE_ADDR'].'\'); return false;';
    $button.= '<button class="btn" onclick="'.$onclick.'" href="#">'.JText::_('PLG_SYSTEM_N3TCOMINGSOON_CFG_IP_FILTER_ADD_CURRENT').'</button>';
    $onclick = '$(\'jform_params_ip_filter\').set(\'value\',\'\'); return false;';
    $button.= ' <button class="btn" onclick="'.$onclick.'" href="#">'.JText::_('PLG_SYSTEM_N3TCOMINGSOON_CFG_IP_FILTER_CLEAR').'</button>';
    return parent::getInput().$button;
	}
}
