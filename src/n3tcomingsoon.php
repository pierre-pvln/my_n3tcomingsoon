<?php
/**
 * @package n3tComingSoon
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2013 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.filesystem.file' );

class plgSystemN3tComingSoon extends JPlugin
{
  private $redirect = false;

	function plgSystemN3tComingSoon(& $subject, $config)
	{
		parent::__construct($subject, $config);
    $this->redirect = false;
	}
  
  private function extensionInstalled($name)
  {
    switch($name) {
      case 'com_acymailing':
        return include_once(JPATH_ADMINISTRATOR.'/components/com_acymailing/helpers/helper.php');
        break;
      case 'com_jnews':
        if (include_once(JPATH_SITE.'/components/com_jnews/defines.php'))
          return include_once(JPATH_ADMINISTRATOR.'/components/'.JNEWS_OPTION.'/classes/class.jnews.php');
        break;
    }
    return false;
  }

  private function getAcyMailingLists() {
    $acymailing_lists = $this->params->def('acymailing_lists', '');
    $listsClass = acymailing_get('class.list');
    $allLists = $listsClass->getLists('listid');
    $acyListsArray = array();
    if(acymailing_level(1)) {
      $allLists = $listsClass->onlyCurrentLanguage($allLists);
    }
    if(acymailing_level(3)){
    	$my = JFactory::getUser();
    	foreach($allLists as $listid => $oneList){
    		if(!$allLists[$listid]->published) continue;
    		if(!acymailing_isAllowed($oneList->access_sub)){
    			$allLists[$listid]->published = false;
    		}
    	}
    }
    if(strpos($acymailing_lists,',') OR is_numeric($acymailing_lists)){
    	$acymailing_lists = explode(',',$acymailing_lists);
    	foreach($allLists as $oneList){
    		if($oneList->published AND in_array($oneList->listid,$acymailing_lists)) $acyListsArray[] = $oneList->listid;
    	}
    }elseif($acymailing_lists == ''){
    	foreach($allLists as $oneList){
    		if(!empty($oneList->published)){$acyListsArray[] = $oneList->listid;}
    	}
    }
    return implode(',',$acyListsArray);
  }

  private function getMessages() {
		$buffer = '';
		$lists = null;
		$messages = JFactory::getApplication()->getMessageQueue();
		if (is_array($messages) && !empty($messages)) {
			foreach ($messages as $msg) {
				if (isset($msg['type']) && isset($msg['message']))
					$lists[$msg['type']][] = $msg['message'];
			}
		}

		if (is_array($lists)) {
			$buffer .= "\n<dl id=\"system-message\">";
			foreach ($lists as $type => $msgs)
			{
				if (count($msgs))
				{
					$buffer .= "\n<dt class=\"" . strtolower($type) . "\">" . JText::_($type) . "</dt>";
					$buffer .= "\n<dd class=\"" . strtolower($type) . " message\">";
					$buffer .= "\n\t<ul>";
					foreach ($msgs as $msg)
					{
						$buffer .= "\n\t\t<li>" . $msg . "</li>";
					}
					$buffer .= "\n\t</ul>";
					$buffer .= "\n</dd>";
				}
			}
			$buffer .= "\n</dl>";
		}
		return $buffer;
  }

  public function onAfterInitialise()   
  {
    $app = JFactory::getApplication();
    if ($app->isSite()) {
      $can_display = false;
      
      $online = $this->params->def('online', '');
      $get_filter = trim($this->params->def('get_filter', ''));
      $ip_filter = trim($this->params->def('ip_filter', ''));
      $acymailing_enabled = $this->params->def('maillist', 'none') == 'acymailing' && $this->extensionInstalled('com_acymailing');
      $jnews_enabled = $this->params->def('maillist', 'none') == 'jnews' && $this->extensionInstalled('com_jnews');
      if ($acymailing_enabled) {
        $maillist_lists = $this->getAcyMailingLists();
        // Backward compatibility. Will be removed.
        $acymailing_lists = $maillist_lists;
      }
      if ($jnews_enabled) {
        $maillist_lists = $this->params->def('acymailing_lists', '');
      }

      if ($online) {        
        $date_online = JFactory::getDate($online);
        $date_now = JFactory::getDate('now');
        $date_now->setTimeZone(new DateTimeZone($app->getCfg('offset')));
        $unix_now = $date_now->toUnix();
        $unix_now += $date_now->getOffsetFromGMT();
        if ($date_online->toUnix() <= $unix_now)
        { 
      	  $db = JFactory::getDBO();
          $query = $db->getQuery(true);
          $query->update('#__extensions')
                ->set('enabled=0')
                ->where('type="plugin"')
                ->where('element="n3tcomingsoon"')
                ->where('folder="system"');
          $db->setQuery((string)$query);
          $db->query();
          $can_display = true;
        } else {
          $seconds_online = $date_online->toUnix() - $unix_now;
          $date_format = $this->params->def('date_format_lc', 'DATE_FORMAT_LC2');
          if ($date_format == '-') $date_format = $this->params->def('custom_date_format', '');
          else $date_format = JText::_($date_format);
          $date_online = $date_online->format($date_format);
        }
      } else {
        $seconds_online = 0;
        $date_online = false;
      }      
      if (!$can_display && $get_filter) {
        $can_display = $app->getUserStateFromRequest( $this->_name.".get_filter", $get_filter, '' );
      }
      if (!$can_display && $ip_filter) {
        $ip_filter = preg_split('/\s*\n\s*/', $ip_filter);
        $can_display = array_search($_SERVER['REMOTE_ADDR'], $ip_filter) !== false;
      }
      if (!$can_display && $acymailing_enabled) {
        $can_display = JRequest::getCmd('option') == 'com_acymailing'
          && JRequest::getCmd('task') == 'optin'
          && JRequest::getInt('ajax') == 1
          && JRequest::getCmd('ctrl') == 'sub'
          && JRequest::getString('hiddenlists') == $acymailing_lists;
      }
      if (!$can_display && $acymailing_enabled) {
        $can_display = JRequest::getCmd('option') == 'com_acymailing'
          && JRequest::getCmd('task') == 'confirm'
          && JRequest::getCmd('ctrl') == 'user';
        if ($can_display) $this->redirect = true;
      }
      if (!$can_display && $jnews_enabled) {
        $can_display = JRequest::getCmd('option') == 'com_jnews'
          && JRequest::getCmd('act') == 'noredsubscribe';
      }
      if (!$can_display && $jnews_enabled) {
        $can_display = JRequest::getCmd('option') == 'com_jnews'
          && JRequest::getCmd('act') == 'confirm';
        if ($can_display) $this->redirect = true;
      }
      if (!$can_display) {
        $custom_file = $this->params->def('custom_file', '');
                
  	    JResponse::setHeader('Content-Type', 'text/html; charset=utf-8');
        JResponse::sendHeaders();        

        $this->loadLanguage('', JPATH_ADMINISTRATOR);
   
        $theme = $this->params->def('theme', 'light');
        $logo = $this->params->def('logo', '');
        $background_image = $this->params->def('background_image', '');
        $favicon = $this->params->def('favicon', '');
        $title = $this->params->def('title', $app->getCfg('sitename'));
        $text = $this->params->def('text', JText::_('PLG_SYSTEM_N3TCOMINGSOON_COMING_SOON'));
        $ga_code = $this->params->def('ga_code', '');
        $ga_mode = $this->params->def('ga_mode', 'single');
        $uri = JURI::getInstance();
        $ga_domain = preg_replace('/^.*\.([^.]*)\.([^.]*)$/i','$1.$2',$uri->getHost());
        $meta_title = $this->params->def('meta_title', $title);
        $meta_desc = $this->params->def('meta_desc', $app->getCfg('MetaDesc'));
        $meta_keys = $this->params->def('meta_keys', $app->getCfg('MetaKeys'));
        $custom_css = $this->params->def('custom_css', '');
        $maillist_text = $this->params->def('maillist_text', '');
        $maillist_name = $this->params->def('maillist_name', 1);
        $facebook_url = $this->params->def('facebook_url', '');
        $twitter_url = $this->params->def('twitter_url', '');
        $googleplus_url = $this->params->def('googleplus_url', '');
        $youtube_url = $this->params->def('youtube_url', '');
        $social_links_target = $this->params->def('social_links_target', '');
        $messages = $this->getMessages();
        
        if ($this->params->def('prepare_content', 0)) {
          $title = JHtml::_('content.prepare', $title);
          $text = JHtml::_('content.prepare', $text); 
          $maillist_text = JHtml::_('content.prepare', $maillist_text);
        }
        
        if ($custom_file && JFile::exists(JPATH_BASE.'/'.$custom_file)) {
          require(JPATH_BASE.'/'.$custom_file);
        } else {                  
          require(JPATH_BASE.'/media/plg_n3tcomingsoon/soon.php');
        }
        $app->close();
      }
    }
  }	

  public function onAfterDispatch()
  {
    if ($this->redirect) {
      $app = JFactory::getApplication();
      $app->redirect('/');
    }
  }
}