<?php

    include '../../mainfile.php';
    require_once XOOPS_ROOT_PATH . '/modules/xentgen/include/xent_gen_tables.php';

    global $xoopsConfig;
    $lang = $xoopsConfig['language'];

    $versioninfo = $moduleHandler->get($xoopsModule->getVar('mid'));
    $module_tables = $versioninfo->getInfo('tables');
