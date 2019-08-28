<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case 'OnWebPagePrerender':
        $output = &$modx->resource->_output;
        preg_match_all('/@[A-Za-z0-9_]{1,}/imu',$output,$match);
        $exclude = array_map('trim', explode(',', $modx->getOption('profilelinkx_exclude')));
        $users = array_diff($match[0], $exclude);

        if (count($users)) {
            $users = array_unique($users);
            $profilelinkx_class = $modx->getOption('profilelinkx_class');
            $profilelinkx_link = $modx->getOption('profilelinkx_link');
            $uniqid = uniqid();
            $chunk = $modx->newObject('modChunk', array('name' => "tmp-$uniqid"));
            $chunk->setContent($profilelinkx_link);
            $chunk->setCacheable(false);

            $array_name = array();
            $array_replace = array();

            foreach ($users as $username) {
                if (!$user = $modx->getObject('modUser', ['username:LIKE' => mb_substr( $username, 1)]))
                    continue;

                $params = array(
                    'class' => $profilelinkx_class,
                    'input' => $modx->getOption('profilelinkx_pass_fullname') ? ($user->Profile->get('fullname') ? : $username) : $username,
                    'username' => $user->get('username')
                );

                $array_name[] = $username;
                $array_replace[] = $chunk->process($params);
            }

            if (count($array_name)) {
                $output = str_ireplace($array_name,$array_replace,$output);
            }
        }

        break;
}