<?php
global $CONFIG;
if (elgg_is_admin_logged_in()) {
	echo "<html><body>";
	$source_username = get_input('source_username');
	$target_username = get_input('target_username');
	if (! $source_username || ! $target_username) {
		echo "<br>".elgg_echo('Missing source or target user names');
		$GLOBALS['DUA_LOG']->FATAL('Missing source or target user names');
		forward (REFERER);
	} else {
		echo "<br>".elgg_echo("source_username=$source_username target_username=$target_username");
		$GLOBALS['DUA_LOG']->FATAL("source_username=$source_username target_username=$target_username");
		$source_user_guid = get_user_by_username($source_username)->getGUID();
		$target_user_guid = get_user_by_username($target_username)->getGUID();
		if (!  $source_user_guid) {
			echo "<br>".elgg_echo("Missing source user GUID for $source_username");
			$GLOBALS['DUA_LOG']->FATAL("Missing source user GUID for $source_username");
			forward (REFERER);
		} elseif (!  $target_user_guid) {
			echo "<br>".elgg_echo("Missing target user GUID for $target_username");
			$GLOBALS['DUA_LOG']->FATAL("Missing target user GUID for $target_username");
			forward (REFERER);
		} else {
			echo "<br>".elgg_echo("source_user_guid=$source_user_guid target_user_guid=$target_user_guid");
			$GLOBALS['DUA_LOG']->FATAL("source_user_guid=$source_user_guid target_user_guid=$target_user_guid");
			echo "<br>".elgg_echo("Processing entities for $source_username");
			$GLOBALS['DUA_LOG']->FATAL("Processing entities for $source_username");
			$entities = elgg_get_entities(array( 'owner_guids' => $source_user_guid,'limit' => 0));
			foreach ($entities as $entity) {
				$entity->owner_guid =  $target_user_guid;
				if ($entity->save()) {
					echo "<br>".elgg_echo("Transferred entity ownership to $target_username for entity $entity->guid $entity->type $entity->subtype");
					$GLOBALS['DUA_LOG']->FATAL("Transferred entity ownership to $target_username for entity $entity->guid $entity->type $entity->subtype");
				} else {
					echo "<br>".elgg_echo("Could not transfer entity ownership to $target_username for entity $entity->guid $entity->type $entity->subtype");
					$GLOBALS['DUA_LOG']->FATAL("Could not transfer entity ownership to $target_username for entity $entity->guid $entity->type $entity->subtype");
				}
			}
			echo "<br>".elgg_echo("Processing metadata for $source_username");
			$GLOBALS['DUA_LOG']->FATAL("Processing metadata for $source_username");
			$metadatas = elgg_get_metadata(array( 'metadata_owner_guids' => $source_user_guid,'limit' => 0));
			foreach ($metadatas as $metadata) {
				$metadata->owner_guid =  $target_user_guid;
				if ($metadata->save()) {
					echo "<br>".elgg_echo("Transferred metadata ownership to $target_username for metadata $metadata->id $metadata->entity_guid");
					$GLOBALS['DUA_LOG']->FATAL("Transferred metadata ownership to $target_username for metadata $metadata->id $metadata->entity_guid");
				} else {
					echo "<br>".elgg_echo("Could not transfer metadata ownership to $target_username for metadata $metadata->id $metadata->entity_guid");
					$GLOBALS['DUA_LOG']->FATAL("Could not transfer metadata ownership to $target_username for metadata $metadata->id $metadata->entity_guid");
				}
			}
			echo "<br>".elgg_echo("Processing annotations for $source_username");
			$GLOBALS['DUA_LOG']->FATAL("Processing annotations for $source_username");
			$annotations = elgg_get_annotations(array('annotation_owner_guid' => $source_user_guid,'limit' => 0));
			foreach ($annotations as $annotation) {
				$annotation->owner_guid =  $target_user_guid;
				if ($annotation->save()) {
					echo "<br>".elgg_echo("Transferred annotation ownership to $target_username for entity $annotation->name $annotation->value $annotation->value_type");
					$GLOBALS['DUA_LOG']->FATAL("Transferred annotation ownership to $target_username for entity $annotation->name $annotation->value $annotation->value_type");
				} else {
					echo "<br>".elgg_echo("Could not transfer annotation ownership to $target_username for entity $annotation->name $annotation->value $annotation->value_type");
					$GLOBALS['DUA_LOG']->FATAL("Could not transfer annotation ownership to $target_username for entity $annotation->name $annotation->value $annotation->value_type");
				}
			}
			echo "<br>".elgg_echo("Processing river items for $source_username");
			$GLOBALS['DUA_LOG']->FATAL("Processing river items for $source_username");
			$river_items = elgg_get_river(array('subject_guid'=> $source_user_guid,'limit' => 0));
			foreach ($river_items as $river_item) {
				$update = "update {$CONFIG->dbprefix}river set subject_guid = {$target_user_guid} where id = {$river_item->id}";
				if (update_data($update)) {
					echo "<br>".elgg_echo("Transferred river item ownership to $target_username for river item $river_item->id $river_item->type $river_item->subtype $river_item->action_type");
					$GLOBALS['DUA_LOG']->FATAL("Transferred river item ownership to $target_username for river item $river_item->id $river_item->type $river_item->subtype $river_item->action_type");
				} else {
					echo "<br>".elgg_echo("Could not transfer river item ownership to $target_username for river item $river_item->id $river_item->type $river_item->subtype $river_item->action_type");
					$GLOBALS['DUA_LOG']->FATAL("Could not transfer river item ownership to $target_username for river item $river_item->id $river_item->type $river_item->subtype $river_item->action_type");
				}
			}
			echo "<br>".elgg_echo("Processing source relationships for $source_username");
			$GLOBALS['DUA_LOG']->FATAL("Processing source relationships for $source_username");
			$source_relationships = get_entity_relationships($source_user_guid);
			foreach ($source_relationships as $source_relationship) {
				if(! check_entity_relationship($target_user_guid,$source_relationship->relationship,$source_relationship->guid_two)) {
					$update = "update {$CONFIG->dbprefix}entity_relationships set guid_one = {$target_user_guid} where id = {$source_relationship->id}";
					if (update_data($update)) {
						echo "<br>".elgg_echo("Transferred relationship to $target_username for relationship $source_relationship->id $source_relationship->guid_one $source_relationship->relationship $source_relationship->guid_two");
						$GLOBALS['DUA_LOG']->FATAL("Transferred relationship to $target_username for relationship $source_relationship->id $source_relationship->guid_one $source_relationship->relationship $source_relationship->guid_two");
					} else {
						echo "<br>".elgg_echo("Could not transfer relationship to $target_username for relationship $source_relationship->id $source_relationship->guid_one $source_relationship->relationship $source_relationship->guid_two");
						$GLOBALS['DUA_LOG']->FATAL("Could not transfer relationship to $target_username for relationship $source_relationship->id $source_relationship->guid_one $source_relationship->relationship $source_relationship->guid_two");
					}
				}
			}
			echo "<br>".elgg_echo("Processing target relationships for $source_username");
			$GLOBALS['DUA_LOG']->FATAL("Processing target relationships for $source_username");
			$target_relationships = get_entity_relationships($source_user_guid,TRUE);
			foreach ($target_relationships as $target_relationship) {
				if(! check_entity_relationship($target_relationship->guid_one,$target_relationship->relationship,$target_user_guid)) {
					$update = "update {$CONFIG->dbprefix}entity_relationships set guid_two = {$target_user_guid} where id = {$target_relationship->id}";
					if (update_data($update)) {
						echo "<br>".elgg_echo("Transferred relationship to $target_username for relationship $target_relationship->id $target_relationship->guid_one $target_relationship->relationship $target_relationship->guid_two");
						$GLOBALS['DUA_LOG']->FATAL("Transferred relationship to $target_username for relationship $target_relationship->id $target_relationship->guid_one $target_relationship->relationship $target_relationship->guid_two");
					} else {
						echo "<br>".elgg_echo("Could not transfer relationship to $target_username for relationship $target_relationship->id $target_relationship->guid_one $target_relationship->relationship $target_relationship->guid_two");
						$GLOBALS['DUA_LOG']->FATAL("Could not transfer relationship to $target_username for relationship $target_relationship->id $target_relationship->guid_one $target_relationship->relationship $target_relationship->guid_two");
					}
				}
			}
			echo "<br>".elgg_echo("Processing collections for $source_username");
			$GLOBALS['DUA_LOG']->FATAL("Processing collections for $source_username");
			$collection_objects = get_user_access_collections($source_user_guid);
			foreach ($collection_objects as $collection_object) {
				$collection=get_access_collection($collection_object->id);
				$update = "update {$CONFIG->dbprefix}access_collections set owner_guid = {$target_user_guid} where id = {$collection->id}";
				if (update_data($update)) {
					echo "<br>".elgg_echo("Transferred access collection ownership to $target_username for collection $collection->id $collection->name");
					$GLOBALS['DUA_LOG']->FATAL("Transferred access collection ownership to $target_username for collection $collection->id $collection->name");
				} else {
					echo "<br>".elgg_echo("Could not transfer access collection ownership to $target_username for collection $collection->id $collection->name");
					$GLOBALS['DUA_LOG']->FATAL("Could not transfer access collection ownership to $target_username for collection $collection->id $collection->name");
				}
			}
			echo "<br>".elgg_echo("Processing collection memberships for $source_username");
			$GLOBALS['DUA_LOG']->FATAL("Processing collection memberships for $source_username");
			$collection_ids = get_access_list($source_user_guid);
			foreach ($collection_ids as $collection_id) {
				if (update_access_collection($collection_id,$target_user_guid)) {
					echo "<br>".elgg_echo("Updated access collection for collection $collection_id");
					$GLOBALS['DUA_LOG']->FATAL("Updated access collection for collection $collection_id");
				} else {
					echo "<br>".elgg_echo("Could not update access collection for collection $collection_id");
					$GLOBALS['DUA_LOG']->FATAL("Could not update access collection for collection $collection_id");
				}
			}
		}
	}
	echo "</body></html>";
	exit;
}
