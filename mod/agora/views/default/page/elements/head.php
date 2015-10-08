<?php
/**
 * The HTML head
 * 
 * JavaScript load sequence (set in views library and this view)
 * ------------------------
 * 1. Elgg's initialization which is inline because it can change on every page load.
 * 2. RequireJS config. Must be loaded before RequireJS is loaded.
 * 3. RequireJS
 * 4. jQuery
 * 5. jQuery migrate
 * 6. jQueryUI
 * 7. elgg.js
 *
 * @uses $vars['title'] The page title
 * @uses $vars['metas'] Array of meta elements
 * @uses $vars['links'] Array of links
 */

$metas = elgg_extract('metas', $vars, array());
$links = elgg_extract('links', $vars, array());
$site_url = elgg_get_site_url();

echo elgg_format_element('title', array(), $vars['title'], array('encode_text' => true));
foreach ($metas as $attributes) {
	echo elgg_format_element('meta', $attributes);
}
foreach ($links as $attributes) {
	echo elgg_format_element('link', $attributes);
}

$js = elgg_get_loaded_js('head');
$css = elgg_get_loaded_css();
?>
        <!-- Web Experience Toolkit (WET) / Boîte à outils de l'expérience Web (BOEW)
        Terms and conditions of use: http://tbs-sct.ircan.gc.ca/projects/gcwwwtemplates/wiki/Terms
        Conditions régissant l'utilisation : http://tbs-sct.ircan.gc.ca/projects/gcwwwtemplates/wiki/Conditions
        -->
        <?php $_PAGE['lang1'] = "eng"; ?>
    <?php $_PAGE['title_eng'] = "Elgg (WET-BOEW)"; ?>
    <?php $_PAGE['issued'] = "2011-12-04"; ?>
    <?php $_PAGE['modified'] = "2013-07-22"; ?>
    <?php $_PAGE['html5'] = "1"; ?>
        <meta name="dcterms.description" content="English description / Description en anglais" />
        <meta name="description" content="English description / Description en anglais" />
        <meta name="keywords" content="English keywords / Mots-clés en anglais" />
        <meta name="dcterms.creator" content="English name of the content author / Nom en anglais de l'auteur du contenu" />
        <meta name="dcterms.subject" title="scheme" content="English subject terms / Termes de sujet en anglais" />

        <!--[if gt IE 8]><!-->
        <script src="<?php echo $site_url;?>mod/agora/views/default/agora/compound.js"></script>
        <script src="<?php echo $site_url;?>mod/agora/views/default/agora/misc.js"></script>
        <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/dist/grids/css/util-min.css"/>
        <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/dist/js/css/pe-ap-min.css"/>
        <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/dist/theme-gcwu-intranet/css/theme-min.css"/>
        <noscript>
        <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/dist/theme-gcwu-intranet/css/theme-ns-min.css"/>
        </noscript>
        <!--[endif]-->




    <!-- CustomCSSStart -->
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/css/site.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/css/component-navigation.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/css/component-lists.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/css/region-header.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/css/region-sidebar.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/css/region-content.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/css/page-feed.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/css/page-user.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/agora/views/default/agora/css/page-groups.css"/>

    <!-- CustomCSSEnd -->

<?php
$elgg_init = elgg_view('js/initialize_elgg');

$html5shiv_url = elgg_normalize_url('vendors/html5shiv.js');
$ie_url = elgg_get_simplecache_url('css', 'ie');

?>

	<!--[if lt IE 9]>
		<script src="<?php echo $html5shiv_url; ?>"></script>
	<![endif]-->

<?php

foreach ($css as $url) {
	echo elgg_format_element('link', array('rel' => 'stylesheet', 'href' => $url));
}

?>
	<!--[if gt IE 8]>
		<link rel="stylesheet" href="<?php echo $ie_url; ?>" />
	<![endif]-->

	<script>
<?php
echo $elgg_init;
$js_polling_control = elgg_get_plugin_setting('js_polling_control', 'agora');
if (isset($js_polling_control)) {
        echo 'elgg.session.js_polling = "'.$js_polling_control.'";';
}
?>
	</script>
<?php
foreach ($js as $url) {
	echo elgg_format_element('script', array('src' => $url));
}

echo elgg_view_deprecated('page/elements/shortcut_icon', array(), "Use the 'head', 'page' plugin hook.", 1.9);

echo elgg_view_deprecated('metatags', array(), "Use the 'head', 'page' plugin hook.", 1.8);
