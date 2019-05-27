<?php
/**
 * @package n3tComingSoon
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2013 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

  defined( '_JEXEC' ) or die( 'Restricted access' );
  $load_mootools = $seconds_online || $background_image || $acymailing_enabled || $jnews_enabled;
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <base href="<?php echo JURI::base(); ?>" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index, follow" />
    <meta name="generator" content="n3t Coming Soon" />
    <meta name="keywords" content="<?php echo $meta_keys; ?>" />
    <meta name="description" content="<?php echo $meta_desc; ?>" />    
    <title><?php echo $meta_title; ?></title>
    <?php if ($favicon) { ?>
    <link type="image/x-icon" rel="shortcut icon" href="<?php echo JURI::root(true); ?><?php echo $favicon; ?>">
    <?php } ?>
    <?php if ($load_mootools) { ?>
    <script src="<?php echo JURI::root(true); ?>/media/system/js/mootools-core.js" type="text/javascript"></script>
    <?php } ?>
    <?php if ($seconds_online) { ?>  
    <script type="text/javascript">
    <!--
      var secondsTotal=<?php echo $seconds_online; ?>;
      
      function calcSoon() {
        secondsTotal--;
        if (secondsTotal<=0) {
          window.location.reload();
        } else {
          var seconds = secondsTotal; 
          var days = Math.floor(seconds/86400);
          seconds = seconds%86400;
          var hours = Math.floor(seconds/3600);
          seconds = seconds%3600;
          var minutes = Math.floor(seconds/60);
          seconds = seconds%60;
          $('soonDays').set('html',days);
          $('soonHours').set('html',hours);
          $('soonMinutes').set('html',minutes);
          $('soonSeconds').set('html',seconds);
          calcSoon.delay(1000);
        }                
      }
        
      window.addEvent('domready', function() {
        calcSoon();
      });                  
    // -->  
    </script>
    <?php } ?>
    <?php if ($background_image) { ?>
    <script src="<?php echo JURI::root(true); ?>/media/plg_n3tcomingsoon/javascript/background.js" type="text/javascript"></script>
    <script type="text/javascript">
    <!--
      window.addEvent('domready', function() {
        new BackgroundImage({src: '<?php echo JURI::root(true).$background_image; ?>'});
      });
    // -->  
    </script>
    <?php } ?>
    <?php if ($acymailing_enabled) { ?>
    <script src="<?php echo JURI::root(true); ?>/media/com_acymailing/js/acymailing_module.js" type="text/javascript"></script>
    <?php acymailing_initJSStrings(''); ?>
    <?php } ?>
    <link rel="stylesheet" href="<?php echo JURI::root(true); ?>/media/plg_n3tcomingsoon/css/<?php echo $theme; ?>.css" type="text/css" />
    <?php if ($custom_css) { ?>
    <style type="text/css">
    <!--
    <?php echo $custom_css; ?>
    -->
    </style>  
    <?php } ?>    
    <?php if ($ga_code) { ?>
    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '<?php echo $ga_code; ?>']);
      <?php if ($ga_mode == 'subdomains' || $ga_mode == 'multi') { ?>
      _gaq.push(['_setDomainName', '<?php echo $ga_domain; ?>']);
      <?php } ?>
      <?php if ($ga_mode == 'multi') { ?>
      _gaq.push(['_setAllowLinker', true]);
      <?php } ?>

      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>
    <?php } ?>
  </head>
  <body>
    <div id="soonWrapper"<?php echo $acymailing_enabled || $jnews_enabled ? ' class="maillistEnabled"' : ''; ?>>
      <h1><?php echo $title; ?></h1>
      <?php if($logo) { ?>
      <img src="<?php echo JURI::root(true); ?><?php echo $logo; ?>" alt="<?php echo $title; ?>" id="soonLogo" />
      <?php } ?>
      <div id="soonText"><?php echo $text; ?></div>
      <?php if($seconds_online) { ?>
      <div id="soonCountDown">
        <?php
          $days_online = (int)($seconds_online / 86400);
          $seconds_online %= 86400;
          $hours_online = (int)($seconds_online / 3600);
          $seconds_online %= 3600;
          $minutes_online = (int)($seconds_online / 60);
          $seconds_online %= 60;
        ?>
        <span id="soonDaysWrapper"><span id="soonDays"><?php echo $days_online;?></span> <span id="soonDaysLabel"><?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_DAYS'); ?></span></span>
        <span id="soonHoursWrapper"><span id="soonHours"><?php echo $hours_online; ?></span> <span id="soonHoursLabel"><?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_HOURS'); ?></span></span>
        <span id="soonMinutesWrapper"><span id="soonMinutes"><?php echo $minutes_online; ?></span> <span id="soonMinutesLabel"><?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_MINUTES'); ?></span></span>
        <span id="soonSecondsWrapper"><span id="soonSeconds"><?php echo $seconds_online;?></span> <span id="soonSecondsLabel"><?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_SECONDS'); ?></span></span>
      </div>
      <?php if($date_online) { ?>
        <h2><?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_DATE_ONLINE').' '.$date_online; ?></h2>
      <?php } ?>
      <?php } ?>
    </div>
    <?php
      if ($facebook_url || $twitter_url || $googleplus_url || $youtube_url) {
        $social_links_target = $social_links_target ? ' target="'.$social_links_target.'"' : '';
    ?>
      <ul id="social">
      <?php if ($facebook_url) { ?>
        <li><a href="<?php echo $facebook_url; ?>" id="facebook" title="<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_FOLLOW_US_ON_FACEBOOK'); ?>"<?php echo $social_links_target; ?>><span><?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_FOLLOW_US_ON_FACEBOOK'); ?></span></a></li>
      <?php } ?>
      <?php if ($twitter_url) { ?>
        <li><a href="<?php echo $twitter_url; ?>" id="twitter" title="<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_FOLLOW_US_ON_TWITTER'); ?>"<?php echo $social_links_target; ?>><span><?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_FOLLOW_US_ON_TWITTER'); ?></span></a></li>
      <?php } ?>
      <?php if ($googleplus_url) { ?>
        <li><a href="<?php echo $googleplus_url; ?>" id="googleplus" title="<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_FOLLOW_US_ON_GOOGLEPLUS'); ?>"<?php echo $social_links_target; ?>><span><?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_FOLLOW_US_ON_GOOGLEPLUS'); ?></span></a></li>
      <?php } ?>
      <?php if ($youtube_url) { ?>
        <li><a href="<?php echo $youtube_url; ?>" id="youtube" title="<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_FOLLOW_US_ON_YOUTUBE'); ?>"<?php echo $social_links_target; ?>><span><?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_FOLLOW_US_ON_YOUTUBE'); ?></span></a></li>
      <?php } ?>
      </ul>
    <?php } ?>
    <?php
      // Any message should be only from maillist component so display them instead of subscription form
      if ($messages) {
    ?>
    <div id="system-message-container" class="maillistWrapper">
      <?php echo $messages; ?>
    </div>
    <?php } elseif ($acymailing_enabled) { ?>
    <div id="acymailing_module_formAcymailing" class="maillistWrapper<?php echo $maillist_name ? '' : ' maillistNoName'; ?>">
      <div id="acymailing_fulldiv_formAcymailing">
        <form name="formAcymailing" method="post" onsubmit="return submitacymailingform('optin','formAcymailing')" action="<?php echo JURI::root(true); ?>/index.php" id="formAcymailing">
          <?php if ($maillist_text) { ?>
          <p class="maillistText">
            <?php echo $maillist_text; ?>
          </p>
          <?php } ?>
          <?php if ($maillist_name) { ?>
          <p id="field_name_formAcymailing" class="maillistField">
            <span class="acyfield_name"><input type="text" value="<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_NAME'); ?>" size="15" name="user[name]" class="inputbox" onblur="if(this.value=='') this.value='<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_NAME'); ?>';" onfocus="if(this.value == '<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_NAME'); ?>') this.value = '';" id="user_name_formAcymailing" /></span>
  				</p>
          <?php } ?>
          <p id="field_email_formAcymailing" class="maillistField">
            <span class="acyfield_email"><input type="text" value="<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_EMAIL'); ?>" size="15" name="user[email]" class="inputbox" onblur="if(this.value=='') this.value='<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_EMAIL'); ?>';" onfocus="if(this.value == '<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_EMAIL'); ?>') this.value = '';" id="user_email_formAcymailing" /></span>
          </p>
          <p class="maillistButton">
  				  <input type="submit" onclick="try{ return submitacymailingform('optin','formAcymailing'); }catch(err){alert('The form could not be submitted '+err);return false;}" name="Submit" value="<?php echo JText::_('PLG_SYSTEM_N3TCOMINGSOON_SUBSCRIBE'); ?>" class="button" />
  				</p>
          <input type="hidden" value="1" name="ajax" />
          <input type="hidden" value="sub" name="ctrl" />
          <input type="hidden" value="optin" name="task" />
          <input type="hidden" value="com_acymailing" name="option" />
          <input type="hidden" value="<?php echo $maillist_lists; ?>" name="hiddenlists" />
  		  </form>
      </div>
    </div>
    <?php } elseif ($jnews_enabled) { ?>
    <div class="maillistWrapper<?php echo $maillist_name ? '' : ' maillistNoName'; ?>">
    <?php if ($maillist_text) { ?>
    <p class="maillistText">
      <?php echo $maillist_text; ?>
    </p>
    <?php } ?>
    <?php
      $jNewsModule = new jnews_module();
      $params = new JRegistry();
      $params->set('listids',$maillist_lists);
      $params->set('showlistname',0);
      $params->set('linear',1);
      $params->set('cssfile','');
      $params->set('shownamefield',$maillist_name);
    	echo $jNewsModule->normal( $params );
    ?>
    </div>
    <?php } ?>

  </body>
</html>
