<?php

class ProfileLinkXItemGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'ProfileLinkXItem';
    public $classKey = 'ProfileLinkXItem';
    public $languageTopics = ['profilelinkx:default'];
    //public $permission = 'view';


    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }

}

return 'ProfileLinkXItemGetProcessor';