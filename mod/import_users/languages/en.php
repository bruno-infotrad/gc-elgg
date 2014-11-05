<?php
/**
 * Elgg import users CSV language pack
 *
 * @package import_users_cvs
 */

$english = array(

		'admin:administer_utilities:import_users' => 'Import users',
		'import_users' => 'Import users',
		'import_users:actions:import_users_csv:upload' => 'Import CSV',
		'import_users:actions:import_users:description' => 'Select the file containing the list of users to import',
		'import_users:actions:import_users:help:csv_format' => 'For a CSV file the following columns (case-sensitive) must be included: <i>"dn", "displayName", "samAccountName", "mail"</i>',
		'import_users:actions:import_users:help:csv_command' => 'For example to get a list of users from an Active Directory this command can be used:<br/><i>csvde -s "xdwdc310.acdi-cida.gc.ca" -f cidausers.csv -d "OU=Domain Users,DC=acdi-cida,DC=gc,DC=ca" -r "(&(objectClass=user)(objectCategory=person)(!userAccountControl:1.2.840.113556.1.4.803:=2))" -l SamAccountName,Distinguishname,displayName,mail -p OneLevel</i>'
);

add_translation("en", $english);
