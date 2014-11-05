<?php
    $french = array(
        'ntlm_sso:settings:label:host' => "Host settings",
        'ntlm_sso:settings:label:connection_search' => "LDAP settings",
        'ntlm_sso:settings:label:hostname' => "Hostname",
        'ntlm_sso:settings:help:hostname' => "Enter the canonical hostname, for example <i>ldap.yourcompany.com</i>",
        'ntlm_sso:settings:label:port' => "The LDAP server port",
    	'ntlm_sso:settings:help:port' => "The LDAP server port. Defaults is 389, which mosts hosts will use.",
        'ntlm_sso:settings:label:version' => "LDAP protocol version",
        'ntlm_sso:settings:help:version' => "LDAP protocol version. Defaults to 3, which most current LDAP hosts will use.",
        'ntlm_sso:settings:label:ldap_bind_dn' => "LDAP bind DN",
        'ntlm_sso:settings:help:ldap_bind_dn' => "Which DN to use for a non-anonymous bind, for exampe <i>cn=admin,dc=yourcompany,dc=com</i>",
        'ntlm_sso:settings:label:ldap_bind_pwd' => "LDAP bind password",
        'ntlm_sso:settings:help:ldap_bind_pwd' => "Which password to use when performing a non-anonymous bind.",
        'ntlm_sso:settings:label:basedn' => "Based DN",
        'ntlm_sso:settings:help:basedn' => "The base DN. Separate with a colon (:) to enter multiple DNs, for example <i>dc=yourcompany,dc=com : dc=othercompany,dc=com</i>",
        'ntlm_sso:settings:label:filter_attr' => "Username filter attribute",
        'ntlm_sso:settings:help:filter_attr' => "The filter to use for the username, common are <i>cn</i>, <i>uid</i> or <i>sAMAccountName</i>.",
        'ntlm_sso:settings:label:search_attr' => "Search attributes",
        'ntlm_sso:settings:help:search_attr' => "Enter search attibutes as key, value pairs with the key being the attribute description, and the value being the actual LDAP attribute.
         <i>firstname</i>, <i>lastname</i> and <i>mail</i> are used to create the Elgg user profile. The following example will work for ActiveDirectory:<br/>
         <blockquote><i>firstname:givenname, lastname:sn, mail:mail</i></blockquote>",
        'ntlm_sso:settings:label:user_create' => "Create users",
        'ntlm_sso:settings:help:user_create' => "Optionally, an account can get created when a LDAP authentication was succesful.",
        'ntlm_sso:settings:label:start_tls' => "Start TLS",
        'ntlm_sso:settings:help:start_tls' => "Start TLS to secure LDAP authentication (server needs to support LDAPS).",
        'ntlm_sso:no_account' => "Vous etes autentifie mais votre compte n'existe pas - Contactez un administrateur du systeme.",
        'ntlm_sso:no_register' => "Nous n'avons pas pu creer votre compte - Contactez un administrateur du systeme."
    );
    
    add_translation('fr', $french);
?>
