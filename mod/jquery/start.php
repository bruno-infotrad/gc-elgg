<?php

	/**
	 * Upgrade Elgg JQuery
	 *
	 * @licence GNU Public License version 2
	 * @link http://twitter.github.com/bootstrap/
	 * @link http://www.marcus-povey.co.uk
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 */
	
	function jquery_init()
	{
            elgg_unregister_js('jquery');
            //elgg_unregister_js('jquery-ui');
            
            elgg_register_js('jquery', elgg_get_config('wwwroot').'mod/jquery/vendor/jquery-1.8.3.min.js','head');
            //elgg_register_js('jquery-ui', elgg_get_config('wwwroot').'mod/jquery/vendor/jquery-ui-1.9.2.min.js');
            //elgg_register_js('jquery-migrate', elgg_get_config('wwwroot').'mod/jquery/vendor/jquery-migrate-1.1.1.min.js');
            
            elgg_load_js('jquery');
            //elgg_load_js('jquery-ui');
            //elgg_load_js('jquery-migrate');
		//elgg_register_simplecache_view('js/lightbox');
		//$lightbox_js_url = elgg_get_simplecache_url('js', 'lightbox');
		//elgg_register_js('lightbox', $lightbox_js_url);

	}
	
	
	elgg_register_event_handler('init','system','jquery_init',1);
