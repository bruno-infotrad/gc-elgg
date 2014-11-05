<?php
/**
 * Likes English language file
 */

$french = array(
	'likes:this' => "a aimé",
	'likes:deleted' => "Votre appréciation a été retiré",
	'likes:see' => "Voir ceux qui ont aimé",
	'likes:remove' => "N'aime pas",
	'likes:notdeleted' => "Il y a eu un problème lors de la suppression d'appréciation",
	'likes:likes' => "Vous aimez maintenant",
	'likes:failure' => "Il y a eu un problème d'appréciation sur cet élément",
	'likes:alreadyliked' => "Vous avez déjà porté votre appréciation sur cet élément",
	'likes:notfound' => "L'élément que vous essayez d'apprécier ne peut être trouvé",
	'likes:likethis' => "Aime",
	'likes:userlikedthis' => "%s aime",
	'likes:userslikedthis' => "%s aiment",
	'likes:river:annotate' => "aime",

	'river:likes' => "aiment %s %s",

	// notifications. yikes.
	'likes:notifications:subject' => "%s aime votre message \"%s\"",
	'likes:notifications:body' =>
"Bonjour %1$s,

%2$s aime votre message '%3$s' sur %4$s

Voir votre message original ici :

%5$s

ou voir le profil de %2$s ici :

%6$s

Merci,
%4$s
",
	
);

add_translation("fr", $french);
