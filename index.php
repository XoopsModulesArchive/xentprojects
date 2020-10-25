<?php

    include 'header.php';
    require XOOPS_ROOT_PATH . '/header.php';
    require_once __DIR__ . '/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/kernel/groupperm.php';

    $myts = MyTextSanitizer::getInstance();
    $eh = new ErrorHandler();

    global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables, $xoopsUser;

    $GLOBALS['xoopsOption']['template_main'] = 'xentprojects_index.html';

    $permObject = new XoopsGroupPermHandler(XoopsDatabaseFactory::getDatabaseConnection());

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]);
    $result = $xoopsDB->query($sql);

    // test si un user est connecté ou non
    if (!$xoopsUser) {
        $id_group = 3;
    } else {
        $id_group = $xoopsUser->getGroups();
    }

    while (false !== ($projects = $xoopsDB->fetchArray($result))) {
        if ($permObject->checkRight('category_read', $projects['ID_PROJECT'], $id_group, $xoopsModule->mid())) {
            $projects['title'] = $myts->displayTarea($projects['title']);

            $projects['introduction'] = $myts->displayTarea($projects['introduction']);

            if (!empty($projects['description'])) {
                $projects['readmore'] = _MA_PRJ_READMORE;
            } else {
                $projects['readmore'] = '';
            }

            $xoopsTpl->append('projects', $projects);
        }
    }

    $xoopsTpl->assign('modTitle', $myts->displayTarea($xoopsModuleConfig['mod_title']));
    $xoopsTpl->assign('modDesc', $myts->displayTarea($xoopsModuleConfig['mod_desc']));
    $xoopsTpl->assign('isImage', $xoopsModuleConfig['isImage']);
    $xoopsTpl->assign('upload_dir', XOOPS_URL . $xoopsModuleConfig['image_upload_dir'] . '/');

    include 'footer.php';
