<div>
	<?php
		global $SESSION;
	?>

	<div class="lang_toggle">
	<script language="javascript" type="text/javascript">
		function form_submit(){
			document.getElementById('formtoggle').submit();
		}
	</script>
		<form action="<?php echo $vars['url']; ?>action/toggle_language/toggle" method="post" name="formtoggle" id="formtoggle">
			
		<?php
		// security tokens.
		echo elgg_view('input/securitytoken');
		?>
		</form>
		<?php
			if ($SESSION['language'] == 'en') { 
		?>
				<span class="active">English</span>
				<a class="not_active" href="javascript:form_submit()">Fran&ccedil;ais</a>
		<?php
			} else {
		?>
				<a class="not_active" href="javascript:form_submit()">English</a>
				<span class="active">Fran&ccedil;ais</span>
		<?php
			}
		?>
	</div>

</div>
