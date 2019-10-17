<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case 'OnWebPagePrerender':
        $is_body = false;
        $output = &$modx->resource->_output;

        preg_match('/<body.*\/body>/s',$output,$matches);

        if ($matches) {
            $is_body = true;
            $out = $matches[0];
        } else {
            $out = $output;
        }
    
        $re = '/@[\w]{1,}(?=(?:[^"]*"[^"]*")*[^"]*$)/imu';

        preg_match_all($re,$out,$match);
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

                $array_name[] = '/'.$username.'(?=(?:[^"]*"[^"]*")*[^"]*$)/imu';
                $array_replace[] = $chunk->process($params);
            }

            if (count($array_name)) {
                $out = preg_replace($array_name,$array_replace,$out);
            }

            if ($is_body) {
                $output = preg_replace('/<body.*\/body>/s',$out,$output);
            } else {
                $output = $out;
            }
        }

        break;
}