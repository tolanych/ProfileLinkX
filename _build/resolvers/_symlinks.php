<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/ProfileLinkX/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/profilelinkx')) {
            $cache->deleteTree(
                $dev . 'assets/components/profilelinkx/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/profilelinkx/', $dev . 'assets/components/profilelinkx');
        }
        if (!is_link($dev . 'core/components/profilelinkx')) {
            $cache->deleteTree(
                $dev . 'core/components/profilelinkx/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/profilelinkx/', $dev . 'core/components/profilelinkx');
        }
    }
}

return true;