<?php $site_url=elgg_get_site_url();?>
    <div id="wb-skip">
        <ul id="wb-tphp">
            <li id="wb-skip1"><a href="#wb-cont">Skip to main content</a></li>
            <li id="wb-skip2"><a href="#wb-nav">Skip to secondary menu</a></li>
        </ul>
    </div>

    <div id="wb-head">
        <div id="wb-head-in">
            <header>
                <!-- HeaderStart -->
                <nav role="navigation">
                    <div id="gcwu-gcnb"><h2>Intranet navigation bar</h2>

                        <div id="gcwu-gcnb-in">
                            <div id="gcwu-intranetnb">
                                <div id="gcwu-intranetnb-in">
					<ul>
						<li id="gcwu-gcnb1"><a href="http://intra/intranet/index.aspx?lang=fra">Accueil</a></li>
						<li id="gcwu-gcnb2"><a href="http://intra/intranet/A-Z.aspx?lang=fra">Index A-Z</a></li>
						<li id="gcwu-gcnb3"><a href="http://intra/intranet/site_plan_site.aspx?lang=fra">Plan du site</a></li>
						<li id="gcwu-gcnb4"><a href="http://publiservice.gc.ca/menu_f.html">Publiservice</a></li>
						<li id="gcwu-gcnb5"><a href="http://www.international.gc.ca/international/index.aspx?lang=fra">Site Internet</a></li>
						<script language="javascript" type="text/javascript">
							function form_submit(){
								document.getElementById('formtoggle').submit();
							}
						</script>
						<form action="<?php echo $site_url; ?>action/toggle_language/toggle" method="post" name="formtoggle" id="formtoggle">
						<?php                                                           
						// security tokens.                                             
						echo elgg_view('input/securitytoken');                          
						?>                                                              
						</form>
						<li id="gcwu-gcnb-lang"><a href="javascript:form_submit()" lang="fr">English</a></li>
					</ul>
                                </div>
                            </div>
                            <div id="gcwu-gcnb-fip">
                                <div id="gcwu-sig">
                                    <div id="gcwu-sig-in">
                                        <object data="<?php echo $site_url;?>mod/agora/views/default/agora/dist/theme-gcwu-intranet/images/sig-static-fra.svg" 
                                                role="img" 
                                                tabindex="-1" aria-label="Gouvernment du Canada" type="image/svg+xml" 
                                                width="217" height="24">
                                            <div id="gcwu-sig-fra" title="Gouvernment du Canada"><img 
                                                    src="<?php echo $site_url;?>mod/agora/views/default/agora/dist/theme-gcwu-intranet/images/sig-fra.png" width="215" 
                                                    height="20" alt="Gouvernment du Canada"/></div>
                                        </object>
                                    </div>
                                </div>
                                <div id="gcwu-wmms">
                                    <div id="gcwu-wmms-in">
                                        <object data="<?php echo $site_url;?>/mod/agora/views/default/agora/dist/theme-gcwu-intranet/images/wmms-static.svg" role="img"
                                                tabindex="-1" aria-label="Symbole du Gouvernment du Canada"
                                                type="image/svg+xml" width="128" height="32">
                                            <div id="gcwu-wmms-fip" title="Symbole du Gouvernment du Canada"><img
                                                    src="<?php echo $site_url;?>/mod/agora/views/default/agora/dist/theme-gcwu-intranet/images/wmms.png" width="126"
                                                    height="30" alt="Symbole du the Gouvernment du Canada"/></div>
                                        </object>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
        </div>
    </div>
