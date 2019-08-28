<?php

if (empty($_REQUEST['action'])) {
    die('Access denied');
} else {
    $action = $_REQUEST['action'];
}

define('MODX_API_MODE', true);
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';

$modx->getService('error', 'error.modError');
$modx->getRequest();
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');
$modx->error->message = null;

$ProfileLinkX = $modx->getService('ProfileLinkX', 'ProfileLinkX', MODX_CORE_PATH . 'components/profilelinkx/model/');

switch ($action) {
    case 'user/one':
        $response = $ProfileLinkX->getUserChunk($_POST['username']);
        break;
    case 'user/list':
        $response = $ProfileLinkX->getUsersList($_POST['search']);
        break;
    default:
        $response = json_encode(array(
            'success' => false,
            'message' => 'unknown action',
        ));
}

if (is_array($response)) {
    $response = json_encode($response);
}

@session_write_close();
exit($response);