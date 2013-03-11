<?php
return array(
	'DB_PREFIX'=>'mb_',
	
	'TMPL_PARSE_STRING'=>array(
		'__HOME__' => '/Public/Home',
		'__ADMIN__' => '/Public/Admin' ,
		// __PUBLIC__/upload  -->  /Public/upload -->http://appname-public.stor.sinaapp.com/upload
		'/Public/Uploads'=>sae_storage_root('Public').'/Uploads',
	)
);