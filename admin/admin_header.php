<?php

    //echo "<link rel='stylesheet' type='text/css' media='all' href='include/admin.css'>";

    require __DIR__ . '/admin_buttons.php';
    include '../../../mainfile.php';
    require dirname(__DIR__, 3) . '/include/cp_header.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once dirname(__DIR__) . '/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/modules/xentprojects/include/functions.php';

    global $xoopsModule;

    $versioninfo = $moduleHandler->get($xoopsModule->getVar('mid'));
    $module_tables = $versioninfo->getInfo('tables');

    if (is_object($xoopsUser)) {
        $xoopsModule = XoopsModule::getByDirname('xentprojects');

        if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
            redirect_header(XOOPS_URL . '/', 1, _NOPERM);

            exit();
        }
    } else {
        redirect_header(XOOPS_URL . '/', 1, _NOPERM);

        exit();
    }

    define('XENT_DB_XENT_GEN_JOBS', 'xent_gen_jobs');
    define('XENT_DB_XENT_GEN_USERS', 'xent_gen_users');
    define('XENT_DB_XENT_GEN_TITLES', 'xent_gen_titles');
    define('XENT_DB_XENT_GEN_LOCATIONS', 'xent_gen_locations');
    define('XENT_DB_XENT_GEN_TYPEPOSTE', 'xent_gen_typeposte');

    $module_id = $xoopsModule->getVar('mid');
    $oAdminButton = new AdminButtons();
    $oAdminButton->AddTitle(_AM_PRJ_ADMINMENUTITLE);

    //$oAdminButton->AddButton(_AM_PRJ_INDEX, "index.php", 'index');
    $oAdminButton->AddButton(_AM_PRJ_ADMINPRJ, 'adminprj.php', 'adminprj');
    $oAdminButton->AddButton(_AM_PRJ_ADMINPERM, 'adminperm.php', 'adminperm');

    $oAdminButton->AddTopLink(_AM_PRJ_PREFERENCES, XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $module_id);
    $oAdminButton->addTopLink(_AM_PRJ_UPDATEMODULE, XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=xentprojects');

    $myts = MyTextSanitizer::getInstance();
