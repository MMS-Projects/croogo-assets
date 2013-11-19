<?php

spl_autoload_register(function($class) {
	$defaultPath = CakePlugin::path('Assets') . 'Vendor' . DS . 'Gaufrette' . DS . 'src' . DS;
	$base = Configure::read('Assets.GaufretteLib');
	if (empty($base)) {
		$base = $defaultPath;
	}
	$class = str_replace('\\', DS, $class);
	if (file_exists($base . $class . '.php')) {
		include ($base . $class . '.php');
	}
});

Configure::write('Wysiwyg.attachmentBrowseUrl', array(
	'plugin' => 'assets',
	'controller' => 'assets_attachments',
	'action' => 'browse',
));

Croogo::mergeConfig('Wysiwyg.actions', array(
	'AssetsAttachments/admin_browse',
));

App::uses('StorageManager', 'Assets.Lib');
StorageManager::config('LocalAttachment', array(
	'description' => 'Local Attachment',
	'adapterOptions' => array(WWW_ROOT . 'assets', true),
	'adapterClass' => '\Gaufrette\Adapter\Local',
	'class' => '\Gaufrette\Filesystem',
));
StorageManager::config('LegacyLocalAttachment', array(
	'description' => 'Local Attachment (Legacy)',
	'adapterOptions' => array(WWW_ROOT . 'uploads', true),
	'adapterClass' => '\Gaufrette\Adapter\Local',
	'class' => '\Gaufrette\Filesystem',
));

$actions = array(
	'Nodes/admin_edit',
	'Blocks/admin_edit',
	'Types/admin_edit',
);
$tabTitle = __d('assets', 'Assets');
foreach ($actions as $action):
	list($controller, ) = explode('/', $action);
	Croogo::hookAdminTab($action, $tabTitle, 'Assets.admin/asset_list');
	Croogo::hookHelper($controller, 'Assets.AssetsAdmin');
endforeach;

Croogo::hookBehavior('Node', 'Assets.LinkedAssets', array('priority' => 9));

CroogoNav::add('media.children.attachments', array(
	'title' => __d('croogo', 'Attachments'),
	'url' => array(
		'admin' => true,
		'plugin' => 'assets',
		'controller' => 'assets_attachments',
		'action' => 'index',
	),
));