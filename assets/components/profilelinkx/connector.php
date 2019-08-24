<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var ProfileLinkX $ProfileLinkX */
$ProfileLinkX = $modx->getService('ProfileLinkX', 'ProfileLinkX', MODX_CORE_PATH . 'components/profilelinkx/model/');
$modx->lexicon->load('profilelinkx:default');

// handle request
$corePath = $modx->getOption('profilelinkx_core_path', null, $modx->getOption('core_path') . 'components/profilelinkx/');
$path = $modx->getOption('processorsPath', $ProfileLinkX->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);