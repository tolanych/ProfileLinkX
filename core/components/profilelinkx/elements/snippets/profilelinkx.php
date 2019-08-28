<?php
if ($ProfileLink = $modx->getService('ProfileLinkX', 'ProfileLinkX', MODX_CORE_PATH . 'components/profilelinkx/model/', [])) {
    $ProfileLink->initialize();
}