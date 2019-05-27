<?php
/**
 * @package n3tComingSoon
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2013 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

JFormHelper::loadFieldClass('list');

class JFormFieldDateFormat extends JFormFieldList
{

	protected $type = 'DateFormat';

	protected function getOptions()
	{
    $date_now = JFactory::getDate();
    $date_now->setTimeZone(new DateTimeZone(JFactory::getApplication()->getCfg('offset')));

		$options = array();
    $options[] = JHtml::_('select.option', 'DATE_FORMAT_LC', $date_now->format(JText::_('DATE_FORMAT_LC'),true), 'value', 'text');
    $options[] = JHtml::_('select.option', 'DATE_FORMAT_LC1', $date_now->format(JText::_('DATE_FORMAT_LC1'),true), 'value', 'text');
    $options[] = JHtml::_('select.option', 'DATE_FORMAT_LC2', $date_now->format(JText::_('DATE_FORMAT_LC2'),true), 'value', 'text');
    $options[] = JHtml::_('select.option', 'DATE_FORMAT_LC3', $date_now->format(JText::_('DATE_FORMAT_LC3'),true), 'value', 'text');
    $options[] = JHtml::_('select.option', 'DATE_FORMAT_LC4', $date_now->format(JText::_('DATE_FORMAT_LC4'),true), 'value', 'text');

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
