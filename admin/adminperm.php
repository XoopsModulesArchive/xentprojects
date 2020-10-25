<?php

    require __DIR__ . '/admin_header.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

    foreach ($_REQUEST as $a => $b) {
        $$a = $b;
    }

    $myts = MyTextSanitizer::getInstance();
    $eh = new ErrorHandler();
    xoops_cp_header();
    echo $oAdminButton->renderButtons('adminperm');

    function PRJAdminPerm()
    {
        global $xoopsDB, $xoopsModule, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $item_list_view = [];

        $block_view = [];

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]);

        $result_view = $xoopsDB->query($sql);

        if ($xoopsDB->getRowsNum($result_view)) {
            while (false !== ($myrow_view = $xoopsDB->fetchArray($result_view))) {
                $item_list_view['cid'] = $myrow_view['ID_PROJECT'];

                $item_list_view['title'] = $myrow_view['title'];

                $form_view = new XoopsGroupPermForm('', $xoopsModule->getVar('mid'), 'category_read', '');

                $block_view[] = $item_list_view;

                foreach ($block_view as $itemlists) {
                    $form_view->addItem($itemlists['cid'], $myts->displayTarea($itemlists['title']));
                }
            }

            echo $form_view->render();
        } else {
            echo _AM_PRJ_NOPROJECTS;
        }
    }

    // ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';

    switch ($op) {
        default:
            PRJAdminPerm();
            break;
    }

    // *************************** Fin de NTS **********************************

     xoops_cp_footer();
