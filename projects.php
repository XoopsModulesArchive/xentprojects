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

    $GLOBALS['xoopsOption']['template_main'] = 'xentprojects_projects.html';

    $permObject = new XoopsGroupPermHandler(XoopsDatabaseFactory::getDatabaseConnection());

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = 0;
    }

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE ID_PROJECT=$id";
    $result = $xoopsDB->query($sql);
    $project = $xoopsDB->fetchArray($result);

    // test si un user est connecté ou non
    if (!$xoopsUser) {
        $id_group = 3;
    } else {
        $id_group = $xoopsUser->getGroups();
    }

    if ($permObject->checkRight('category_read', $id, $id_group, $xoopsModule->mid())) {
        $project['title'] = $myts->displayTarea($project['title']);

        $project['introduction'] = $myts->displayTarea($project['introduction']);

        $project['description'] = $myts->displayTarea($project['description']);

        $xoopsTpl->append('project', $project);
    }

    $xoopsTpl->assign('projectBack', _MA_PRJ_BACK);
    $xoopsTpl->assign('modTitle', $myts->displayTarea($xoopsModuleConfig['mod_title']));
    $xoopsTpl->assign('modDesc', $myts->displayTarea($xoopsModuleConfig['mod_desc']));
    $xoopsTpl->assign('isImage', $xoopsModuleConfig['isImage']);
    $xoopsTpl->assign('upload_dir', XOOPS_URL . $xoopsModuleConfig['image_upload_dir'] . '/');

    include 'footer.php';
