<?php
/**
 * The standard HTML head
 *
 * @uses $vars['title'] The page title
 */

// Set title
if (empty($vars['title'])) {
	$title = elgg_get_config('sitename');
} else {
	$title = elgg_get_config('sitename') . ": " . $vars['title'];
}
$site_url = elgg_get_site_url();

global $autofeed;
if (isset($autofeed) && $autofeed == true) {
	$url = full_url();
	if (substr_count($url,'?')) {
		$url .= "&view=rss";
	} else {
		$url .= "?view=rss";
	}
	$url = elgg_format_url($url);
	$feedref = <<<END

	<link rel="alternate" type="application/rss+xml" title="RSS" href="{$url}" />

END;
} else {
	$feedref = "";
}

$js = elgg_get_loaded_js('head');
$css = elgg_get_loaded_css();

$version = get_version();
$release = get_version(true);
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

	<!--[if lte IE 8]>
	<script src="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/js/jquery-ie.min.js"></script>
	<script src="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/js/polyfills/html5shiv-min.js"></script>
	<link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/grids/css/util-ie-min.css"/>
	<link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/js/css/pe-ap-ie-min.css"/>
	<link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/theme-gcwu-intranet/css/theme-ie-min.css"/>
	<noscript>
	<link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/theme-gcwu-intranet/css/theme-ns-ie-min.css"/>
	</noscript>
	<![endif]-->
	<!--[if gt IE 8]><!-->
	<script src="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/js/jquery.min.js"></script>
	<script src="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/compound.js"></script>
        <script src="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/misc.js"></script>
	<link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/grids/css/util-min.css"/>
	<link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/js/css/pe-ap-min.css"/>
	<link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/theme-gcwu-intranet/css/theme-min.css"/>
	<noscript>
	<link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/theme-gcwu-intranet/css/theme-ns-min.css"/>
	</noscript>
	<!--<![endif]-->




    <!-- CustomCSSStart -->
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/site.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/component-navigation.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/component-lists.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/region-header.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/region-sidebar.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/region-content.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/page-feed.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/page-user.css"/>
    <link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/page-groups.css"/>

    <!--[if IE 7]><link rel="stylesheet" href="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/css/ie7.css"/><!--<![endif]-->
    <!-- CustomCSSEnd -->



	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="ElggRelease" content="<?php echo $release; ?>" />
	<meta name="ElggVersion" content="<?php echo $version; ?>" />
	<title><?php echo $title; ?></title>
	<?php echo elgg_view('page/elements/shortcut_icon', $vars); ?>

<?php foreach ($css as $link) { ?>
	<link rel="stylesheet" href="<?php echo $link; ?>" type="text/css" />
<?php } ?>

<?php
	$ie_url = elgg_get_simplecache_url('css', 'ie');
	$ie6_url = elgg_get_simplecache_url('css', 'ie6');
?>
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="<?php echo $ie_url; ?>" />
	<![endif]-->
	<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php echo $ie6_url; ?>" />
	<![endif]-->

<?php foreach ($js as $script) { ?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<script type="text/javascript">
	<?php echo elgg_view('js/initialize_elgg'); ?>
</script>

<?php
echo $feedref;

$metatags = elgg_view('metatags', $vars);
if ($metatags) {
	elgg_deprecated_notice("The metatags view has been deprecated. Extend page/elements/head instead", 1.8);
	echo $metatags;
}
