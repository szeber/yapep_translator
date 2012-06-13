<?php
/**
 * Basic configurations
 */

use YapepBase\Config;


Config::getInstance()->set(array(
	// Error logging
	'resource.log.error.facility'            => LOG_LOCAL5,
	'resource.log.error.applicationIdent'    => PROGRAM_NAME,
	'resource.log.error.includeSapiName'     => false,
	'resource.log.error.addPid'              => true,


	'resource.storage.debugData.path'           => '/var/log/application/' . PROGRAM_NAME . '/error',
	'resource.storage.debugData.storePlainText' => true,
	'resource.storage.debugData.fileSuffix'     => '.log',


	// Db configs
	'system.database.paramPrefix' => '_',

	'resource.database.translation.rw.backendType'              => 'mysql',
	'resource.database.translation.rw.host'                     => getenv('YAPEPBASE_TEST_MYSQL_RW_HOST'),
	'resource.database.translation.rw.user'                     => getenv('YAPEPBASE_TEST_MYSQL_RW_USER'),
	'resource.database.translation.rw.password'                 => getenv('YAPEPBASE_TEST_MYSQL_RW_PASSWORD'),
	'resource.database.translation.rw.database'                 => getenv('YAPEPBASE_TEST_MYSQL_RW_DATABASE'),
	'resource.database.translation.rw.charset'                  => 'utf8',
	'resource.database.translation.rw.useTraditionalStrictMode' => true,

	'resource.database.translation.ro.backendType'              => 'mysql',
	'resource.database.translation.ro.host'                     => getenv('YAPEPBASE_TEST_MYSQL_RO_HOST'),
	'resource.database.translation.ro.user'                     => getenv('YAPEPBASE_TEST_MYSQL_RO_USER'),
	'resource.database.translation.ro.password'                 => getenv('YAPEPBASE_TEST_MYSQL_RO_PASSWORD'),
	'resource.database.translation.ro.database'                 => getenv('YAPEPBASE_TEST_MYSQL_RO_DATABASE'),
	'resource.database.translation.ro.charset'                  => 'utf8',
	'resource.database.translation.ro.useTraditionalStrictMode' => true,
));