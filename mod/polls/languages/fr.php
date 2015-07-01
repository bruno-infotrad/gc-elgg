<?php
return array(
/**
 * Menu items and titles
 */
	'poll' => "Sondage",
	'polls:add' => "Nouveau sondage",
	'polls' => "Sondages",
	'polls:votes' => "votes",
	'polls:user' => "Sondage de %s",
	'polls:group_polls' => "Sondages de groupes",
	'polls:group_polls:listing:title' => "Sondages de %s",
	'polls:user:friends' => "Sondage des amis de %s",
	'polls:your' => "Vos sondages",
	'polls:not_me' => "Sondages de %s",
	'polls:posttitle' => "Sondages de %s : %s",
	'polls:friends' => "Sondages des amis",
	'polls:not_me_friends' => "Sondages des amis de %s",
	'polls:yourfriends' => "Les plus récents sondages de vos amis",
	'polls:everyone' => "Sondages pour tout le site",
	'polls:read' => "Lire le sondage",
	'polls:addpost' => "Créer un sondage",
	'polls:editpost' => "Éditer un sondage : %s",
	'polls:edit' => "Éditer un sondage",
	'polls:text' => "Texte du sondage",
	'polls:strapline' => "%s",			
	'item:object:poll' => 'Sondages',
	'item:object:poll_choice' => "Choix pour un sondage",
	'polls:question' => "Question pour un sondage",
	'polls:responses' => "Choix de réponse",
	'polls:results' => "[+] Montrer les résultats",
	'polls:show_results' => "Montrer les résultats",
	'polls:show_poll' => "Montrer le sondage",
	'polls:add_choice' => "Ajouter le choix de réponse",
	'polls:delete_choice' => "Supprimer ce choix",
	'polls:settings:group:title' => "Sondages de groupe",
	'polls:settings:group_polls_default' => "oui, activé implicitement",
	'polls:settings:group_polls_not_default' => "oui, désactivé implicitement",
	'polls:settings:no' => "non",
	'polls:settings:group_profile_display:title' => "Si les sondages de groupe sont activés, où afficher le contenu des sondages dans les profils de groupe ?",
	'polls:settings:group_profile_display_option:left' => "gauche",
	'polls:settings:group_profile_display_option:right' => "droite",
	'polls:settings:group_profile_display_option:none' => "aucun",
	'polls:settings:group_access:title' => "Si les sondages de groupe sont activés, qui crée les sondages ?",
	'polls:settings:group_access:admins' => "propriétaires de groupe et administrateurs seulement",
	'polls:settings:group_access:members' => "n'importe quel membre du groupe",
	'polls:settings:front_page:title' => "Les administrateurs peuvent configurer un sondage sur la page d'accueil (avec autorisation pour soutien thématique)",
	'polls:settings:status' => "État",
	'polls:open' => "En cours",
	'polls:closed' => "Terminé",
	'polls:closedlongtext' => "Ce sondage est maintenant terminé",
	'polls:none' => "Aucun sondage trouvé.",
	'polls:permission_error' => "Vous n'avez pas la permission requise pour éditer ce sondage.",
	'polls:vote' => "Vote",
	'polls:login' => "Veuillez ouvrir une session pour voter pour ce sondage.",
	'group:polls:empty' => "Aucun sondage",
	'polls:settings:site_access:title' => "Qui peut créer des sondages pour tout le site ?",
	'polls:settings:site_access:admins' => "Seulement les administrateurs",
	'polls:settings:site_access:all' => "Tout utilisateur actif",
	'polls:can_not_create' => "Vous n'avez pas la permission requise pour créer des sondages.",
	'polls:front_page_label' => "Placer ce sondage à la page d'accueil.",
/**
* poll widget
**/
	'polls:latest_widget_title' => "Plus récents sondages dans la collectivité",
	'polls:latest_widget_description' => "Affiche les sondages les plus récents.",
	'polls:my_widget_title' => "Mes sondages",
	'polls:my_widget_description' => "Ce gadget logiciel permet d'afficher vos sondages.",
	'polls:widget:label:displaynum' => "Nombre de sondages à afficher.",
	'polls:individual' => "Sondage le plus récent",
	'poll_individual_group:widget:description' => "Afficher le sondage le plus récent pour ce groupe.",
	'poll_individual:widget:description' => "Afficher votre sondage le plus récent",
	'polls:widget:no_polls' => "Il n'y a encore aucun sondage pour %s.",
	'polls:widget:nonefound' => "Aucun sondage trouvé.",
	'polls:widget:think' => "Dites à %s ce que vous pensez !",
	'polls:enable_polls' => "Activer les sondages",
	'polls:group_identifier' => "(dans %s)",
	'polls:noun_response' => "réponse",
	'polls:noun_responses' => "réponses",
	'polls:settings:yes' => "oui",
	'polls:settings:no' => "non",
/**
* poll river
**/
	'polls:settings:create_in_river:title' => "Montrer la création de sondages dans le flux des activités",
	'polls:settings:vote_in_river:title' => "Montrer le vote pour les sondages dans le flux des activités",
	'river:create:object:poll' => '%s a créé un sondage %s',
	'river:vote:object:poll' => '%s a voté dans le cadre du sondage %s',
	'river:comment:object:poll' => '%s a fait un commentaire sur le sondage %s',
/**
 * Status messages
 */
	'polls:added' => "Votre sondage a été créé.",
	'polls:edited' => "Votre sondage a été sauvegardé.",
	'polls:responded' => "Merci d'avoir répondu; votre vote a été consigné.",
	'polls:deleted' => "Votre sondage a été supprimé avec succès.",
	'polls:totalvotes' => "Nombre total de votes : ",
	'polls:voted' => "Votre vote a été consigné pour ce sondage. Merci d'avoir voté.",
/**
* Notification message
*/
	'poll:notify_new_poll' => 'Un nouveau sondage a été affiché',
	'polls:notify:summary' => 'Nouveau sondage nommé %s',
	'polls:notify:subject' => "Un nouveau sondage: %s",
	'polls:notify:body' =>
'%s a ajouté un nouveau sondage: %s

%s

Voir et commenter ce sondage:
%s
',
/**
 * Error messages
 */
	'polls:save:failure' => "Votre vote n'a pu être sauvegardé. Veuillez essayer de nouveau.",
	'polls:blank' => "Désolé : vous devez inscrire à la fois la question et les réponses pour créer un sondage.",
	'polls:novote' => "Désolé : vous devez choisir une option pour voter pour ce sondage.",
	'polls:notfound' => "Désolé : le sondage indiqué est introuvable.",
	'polls:nonefound' => "Aucun sondage trouvé pour %s",
	'polls:notdeleted' => "Désolé : impossible de supprimer ce sondage."
);
