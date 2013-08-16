<?php

class UW_Plugin_Hooks extends UW_CMS_Shivs
{

  function UW_Plugin_Hooks()
  {
    // Sharedaddy 
    add_filter('sharing_permalink', array( $this, 'uw_remove_cms_from_plugin_permalinks') );
    add_filter('sharing_show', array( $this, 'uw_sharing_show') );

    // Contact Form 7 
    add_filter('wpcf7_form_class_attr', array( $this, 'uw_add_wpcf7_bootstrap_class' ) );

    add_filter('wpcf7_ajax_loader', array( $this, 'remove_cms_from_admin_url') );
    add_filter('wpcf7_form_action_url', array( $this, 'remove_cms_from_admin_url') );

  }

  /**
   * Bug fix for the plugins that need site url. The plugin uses the default permalink 
   *  of the post which contains /cms/. This function and filter removes /cms/ from 
   *  the permalink.
   */
  function uw_remove_cms_from_plugin_permalinks($url) 
  {
      return is_local() ? $url : str_replace('/cms/','/', $url);  
  }

  /**
   * Add the Boostrap class to the Contact Form 7 form tag
   */
  function uw_add_wpcf7_bootstrap_class($class) 
  {
      return $class . ' form-horizontal';
  }

  /**
   * If the blogroll is on the front page 
   * don't show any of the sharing links
   */
  function uw_sharing_show($show) 
  {
    return is_front_page() ? false : $show;
  }

}

new UW_Plugin_Hooks;
