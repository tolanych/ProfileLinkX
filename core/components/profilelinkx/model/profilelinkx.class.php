<?php

class ProfileLinkX
{
    /** @var modX $modx */
    public $modx;
    public $initialized = false;

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/profilelinkx/';
        $assetsUrl = MODX_ASSETS_URL . 'components/profilelinkx/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'actionUrl' => $assetsUrl . 'action.php',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);

        $this->modx->addPackage('profilelinkx', $this->config['modelPath']);
        //$this->modx->lexicon->load('profilelinkx:default');
    }

    public function initialize() {
        if (!$this->initialized) {
            $json_param = [
                'mode' => $this->modx->getOption('profilelinkx_env'),
                'class' => $this->modx->getOption('profilelinkx_class'),
                'textarea' => $this->modx->getOption('profilelinkx_sug_textarea'),
                'jsUrl' => $this->config['jsUrl'],
                'actionUrl' => $this->config['actionUrl'],
            ];
            $this->modx->regClientStartupScript('<script>if (typeof ProfileLinkXConfig == "undefined")  {ProfileLinkXConfig=' . json_encode($json_param) . ';}</script>',
            true);
            $this->modx->regClientScript($this->config['jsUrl'] . 'web/profilelinkx.js');
            $this->modx->regClientCSS($this->config['cssUrl'] . 'web/main.css');
            $this->initialized = true;
        }
    }

    /**
     * @param string $username
     */
    public function getUserChunk($username) {
        if (!$user = $this->modx->getObject('modUser', ['username' => $username])) {
            return ['success' => false];
        }

        if (!$chunkname = $this->modx->getOption('profilelinkx_chunk')) {
            return ['success' => false];
        }

        $data = array_merge($user->toArray('modUser_'), $user->Profile->toArray('modProfile_'));

        if ($pdoFetch = $this->modx->getService('pdoFetch')) {
            $answer = $pdoFetch->getChunk($chunkname,$data);
        }
        else {
            $answer = $this->modx->getChunk($chunkname,$data);
        }

        return ['success' => true, 'html' => $answer];
    }

    /**
     * Search by term in username and fullname
     * 
     * @param string $search
     */
    public function getUsersList($search) {
        $results = [];
        $search = '%'.$search.'%';
        $q = $this->modx->newQuery('modUser');
        $q->select('Profile.fullname, modUser.username');
        $q->leftJoin('modUserProfile','Profile','modUser.id = Profile.internalKey');
        $q->where([
            ['modUser.username:LIKE' => $search],
            ['OR:Profile.fullname:LIKE' => $search]
        ]);

        if ($exclude_users = $this->modx->getOption('profilelinkx_sug_exclude')) {
            $exclude_users = array_map('trim', explode(',', $exclude_users));
            if (is_array($exclude_users) && count($exclude_users)) {
                $q->where(array(
                    'modUser.username:NOT IN' => $exclude_users,
                ));
            }
        }

        if ($exclude_group = $this->modx->getOption('profilelinkx_sug_exclude_group')) {
            $exclude_group = array_map('trim', explode(',', $exclude_group));

            if (is_array($exclude_group) && count($exclude_group)) {
                $q->distinct();
                $q->leftJoin('modUserGroupMember','UserGroupMembers');
                $q->where(array(
                    'UserGroupMembers.user_group:NOT IN' => $exclude_group,
                    'OR:UserGroupMembers.user_group:IS' => null,
                ));
            }
        }

        $q->sortby('modUser.username');
        $q->limit(10);
        $q->prepare();
        $q->stmt->execute();
        $results = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['success' => true, 'results' => $results];
    }

}