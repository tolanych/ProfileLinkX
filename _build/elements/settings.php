<?php

return [
    'env' => [
        'xtype' => 'textfield',
        'value' => 'default',
        'area' => 'profilelinkx_main',
    ],
    'class' => [
        'xtype' => 'textfield',
        'value' => 'profilelinkx',
        'area' => 'profilelinkx_main',
    ],
    'link' => [
        'xtype' => 'textfield',
        'value' => '<a href="/account/[[+username]]/" class="[[+class]]" attr-user="[[+username]]">[[+input]]</a>',
        'area' => 'profilelinkx_main',
    ],
    'use_tooltip' => [
        'xtype' => 'combo-boolean',
        'value' => true,
        'area' => 'profilelinkx_main',
    ],
    'chunk' => [
        'xtype' => 'textfield',
        'value' => 'tpl.ProfileLinkX.tooltip',
        'area' => 'profilelinkx_main',
    ],
    'exclude' => [
        'xtype' => 'textfield',
        'value' => '@media, @import',
        'area' => 'profilelinkx_main',
    ],
    'pass_fullname' => [
        'xtype' => 'combo-boolean',
        'value' => false,
        'area' => 'profilelinkx_main',
    ],
    'sug_textarea' => [
        'xtype' => 'textfield',
        'value' => '.user-suggest',
        'area' => 'profilelinkx_main',
    ],
    'sug_exclude' => [
        'xtype' => 'textfield',
        'value' => 'admin',
        'area' => 'profilelinkx_main',
    ],
    'sug_exclude_group' => [
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'profilelinkx_main',
    ],
];