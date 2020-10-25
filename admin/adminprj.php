<?php

    require __DIR__ . '/admin_header.php';
    require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

    foreach ($_REQUEST as $a => $b) {
        $$a = $b;
    }

    $eh = new ErrorHandler();
    xoops_cp_header();
    echo $oAdminButton->renderButtons('adminprj');

    OpenTable();
    echo "<div class='adminHeader'>" . _AM_PRJ_ADMINTITLE . '</div><br>';

    function PRJShowProjects()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]);

        $result = $xoopsDB->query($sql);

        echo "
        	<table width='100%' class='outer' cellspacing='1'>
                <tr>
                    <th align='center' width='18%'>" . _AM_PRJ_TITLE . "</th>
                    <th width='20%'>" . _AM_PRJ_INTRODUCTION . "</th>
                    <th width='20%'>" . _AM_PRJ_DESC . "</th>
                    <th align='center' width='6%'>" . _AM_PRJ_ACTIVE . "</th>
                    <th align='center' width='6%'>" . _AM_PRJ_PRIORITY . "</th>
                    <th align='center' width='10%'>" . _AM_PRJ_IMAGE . "</th>
                    <th align='center' width='10%'>" . _AM_PRJ_IMAGEWHERETODISPLAYED . "</th>
                    <th align='center' width='10%'>" . _AM_PRJ_OPTIONS . '</th>
                </tr>
        ';

        while (false !== ($projects = $xoopsDB->fetchArray($result))) {
            $id = $projects['ID_PROJECT'];

            if (!empty($projects['pict'])) {
                $img = "<img src='" . XOOPS_URL . $xoopsModuleConfig['image_upload_dir'] . '/' . $projects['pict'] . "'></img>";
            } else {
                $img = '';
            }

            $introduction = xoops_substr($myts->displayTarea($projects['introduction'], 1, 1, 1, 0), 0, 100, $trimmarker = '...');

            $description = xoops_substr($myts->displayTarea($projects['description'], 1, 1, 1, 0), 0, 100, $trimmarker = '...');

            echo "
                <tr valign='middle' align='left'>
                    <td class='even'><b>" . $myts->displayTarea($projects['title'], 1, 1, 1, 0) . "</b></td>
                    <td class='even'>" . $introduction . "</td>
                    <td class='even'>" . $description . "</td>
                    <td class='even' align='center'>";

            if (0 == $projects['status']) {
                echo _AM_PRJ_YES;
            } elseif (1 == $projects['status']) {
                echo _AM_PRJ_NO;
            }

            echo "</td>
                    <td class='even' align='center'>" . $projects['priority'] . "</td>
                    <td class='even' align='center'>$img</td>
                    <td class='even' align='center'>";

            if (0 == $projects['imagewhereto']) {
                echo _AM_PRJ_IMAGEWHERETODISPLAYEDOPT0;
            } elseif (1 == $projects['imagewhereto']) {
                echo _AM_PRJ_IMAGEWHERETODISPLAYEDOPT1;
            } elseif (2 == $projects['imagewhereto']) {
                echo _AM_PRJ_IMAGEWHERETODISPLAYEDOPT2;
            }

            echo "</td>
                    <td class='even' align='center'><a href='adminprj.php?op=PRJEditProject&id=$id'>" . _AM_PRJ_EDIT . "</a><br><br><a href='adminprj.php?op=PRJAreYouSureDeleteProject&id=$id'>" . _AM_PRJ_DELETE . '</a></td>
                </tr>
            ';
        }

        echo '
        	</table>
        ';
    }

    function PRJEditProject()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            if (!empty($_POST['id'])) {
                $id = $_POST['id'];
            } else {
                $id = 0;
            }
        }

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE ID_PROJECT=$id";

        $result = $xoopsDB->query($sql);

        $project = $xoopsDB->fetchArray($result);

        if (!empty($project['ID_PROJECT'])) {
            if (!empty($project['pict'])) {
                $img = "<img src='" . XOOPS_URL . $xoopsModuleConfig['image_upload_dir'] . '/' . $project['pict'] . "'></img>";
            } else {
                $img = '';
            }

            $sform = new XoopsThemeForm(_AM_PRJ_EDIT, 'editprj', xoops_getenv('PHP_SELF'));

            $sform->setExtra('enctype="multipart/form-data"');

            $sform->addElement(new XoopsFormText(_AM_PRJ_TITLE, 'title', 100, 255, $project['title']));

            $sform->addElement(new XoopsFormTextArea(_AM_PRJ_INTRODUCTION, 'intro', $project['introduction'], 10, 50));

            $sform->addElement(new xoopsFormDhtmlTextArea(_AM_PRJ_DESC, 'desc', $project['description'], 10, 50));

            $sform->addElement(makeSelect(_AM_PRJ_ACTIVE, 'status_select', $project['status'], makeStatusArray()));

            $sform->addElement(new XoopsFormText(_AM_PRJ_PRIORITY, 'priority', 2, 2, $project['priority']));

            $sform->addElement(new XoopsFormfile(_AM_PRJ_IMAGE, 'pict', 250000));

            $sform->addElement(makeSelect(_AM_PRJ_IMAGEWHERETODISPLAYED, 'imagewhereto_select', $project['imagewhereto'], makeImageDisplayedArray(), 3));

            $noimagechk = new XoopsFormCheckBox('', 'noimagechk', 0);

            $noimagechk->addOption(1, '&nbsp;&nbsp;&nbsp;' . _AM_PRJ_NOIMAGE);

            $sform->addElement($noimagechk);

            $sform->addElement(new XoopsFormLabel('', "<br>$img"));

            $save_button = new XoopsFormButton('', 'add', _AM_PRJ_SAVE, 'submit');

            $save_button->setExtra("onmouseover='document.editprj.op.value=\"PRJSaveEdit\"'");

            $cancel_button = new XoopsFormButton('', 'add', _AM_PRJ_CANCEL, 'submit');

            $cancel_button->setExtra("onmouseover='document.editprj.op.value=\"PRJShowProjects\"'");

            $button_tray = new XoopsFormElementTray('', '');

            $button_tray->addElement($save_button);

            $button_tray->addElement($cancel_button);

            $sform->addElement($button_tray);

            $sform->addElement(new XoopsFormHidden('id_prj', $project['ID_PROJECT']));

            $sform->addElement(new XoopsFormHidden('id', (string)$id));

            $sform->addElement(new XoopsFormHidden('op', 'PRJSaveEdit'));

            $sform->addElement(new XoopsFormHidden('original_pict', $project['pict']));

            $sform->display();
        }  

        // aucun projet qui correspond au id, on affiche un message d'erreur
    }

    function PRJNewProject()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        if (!empty($_GET['title'])) {
            $title = $_GET['title'];
        } else {
            $title = '';
        }

        if (!empty($_GET['intro'])) {
            $intro = $_GET['intro'];
        } else {
            $intro = '';
        }

        if (!empty($_GET['desc'])) {
            $desc = $_GET['desc'];
        } else {
            $desc = '';
        }

        if (!empty($_GET['status'])) {
            $status = $_GET['status'];
        } else {
            $status = 0;
        }

        if (!empty($_GET['priority'])) {
            $priority = $_GET['priority'];
        } else {
            $priority = 0;
        }

        if (!empty($_GET['imagewhereto'])) {
            $imagewhereto = $_GET['imagewhereto'];
        } else {
            $imagewhereto = 0;
        }

        $sform = new XoopsThemeForm(_AM_PRJ_NEWPROJECT, 'addprj', xoops_getenv('PHP_SELF'));

        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormText(_AM_PRJ_TITLE, 'title', 100, 255, $title), true);

        $sform->addElement(new XoopsFormTextArea(_AM_PRJ_INTRODUCTION, 'intro', $intro, 10, 50));

        $sform->addElement(new xoopsFormDhtmlTextArea(_AM_PRJ_DESC, 'desc', $desc, 10, 50));

        $sform->addElement(makeSelect(_AM_PRJ_ACTIVE, 'status_select', $status, makeStatusArray()));

        $sform->addElement(new XoopsFormText(_AM_PRJ_PRIORITY, 'priority', 2, 2, $priority));

        $sform->addElement(new XoopsFormfile(_AM_PRJ_IMAGE, 'pict', $xoopsModuleConfig['max_file_size']));

        $sform->addElement(makeSelect(_AM_PRJ_IMAGEWHERETODISPLAYED, 'imagewhereto_select', $imagewhereto, makeImageDisplayedArray(), 3));

        $sform->addElement($label);

        $save_button = new XoopsFormButton('', 'add', _AM_PRJ_SAVE, 'submit');

        $save_button->setExtra("onmouseover='document.addprj.op.value=\"PRJSaveAdd\"'");

        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement($save_button);

        $sform->addElement($button_tray);

        $sform->addElement(new XoopsFormHidden('op', ''));

        $sform->display();
    }

    function PRJSaveAdd($title, $intro, $desc, $status, $priority, $pict, $imagewhereto)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $title = $myts->addSlashes(str_replace("'", '’', $title));

        $intro = $myts->addSlashes(str_replace("'", '’', $intro));

        $desc = $myts->addSlashes(str_replace("'", '’', $desc));

        if (!empty($pict)) {
            if (true === uploadFile(XOOPS_ROOT_PATH . $xoopsModuleConfig['image_upload_dir'], explode(';', $xoopsModuleConfig['image_extension']), $xoopsModuleConfig['max_file_size'], $_POST['xoops_upload_file'][0])) {
                $sql = 'INSERT INTO ' . $xoopsDB->prefix($module_tables[0]) . " (title, introduction, description, status, priority, pict, imagewhereto) VALUES ('$title', '$intro', '$desc', $status, $priority, '$pict', $imagewhereto)";

                $xoopsDB->query($sql);

                if (0 == $xoopsDB->errno()) {
                    redirect_header('adminprj.php', 1, _AM_PRJ_DBUPDATED);
                } else {
                    redirect_header('adminprj.php?', 4, $xoopsDB->error());
                }
            } else {
                // erreur dans le type du fichier uploadé

                redirect_header("adminprj.php?op=PRJNewProject&title=$title&intro=$intro&desc=$desc&status=$status&priority=$priority&pict=$pict&imagewhereto=$imagewhereto", 4, sprintf(_AM_PRJ_ERRORUPLOADEDFILE, $xoopsModuleConfig['image_extension'], $xoopsModuleConfig['max_file_size']));
            }
        } else {
            $sql = 'INSERT INTO ' . $xoopsDB->prefix($module_tables[0]) . " (title, introduction, description, status, priority, pict, imagewhereto) VALUES ('$title', '$intro', '$desc', $status, $priority, '$pict', $imagewhereto)";

            $xoopsDB->query($sql);

            if (0 == $xoopsDB->errno()) {
                redirect_header('adminprj.php', 1, _AM_PRJ_DBUPDATED);
            } else {
                redirect_header('adminprj.php?', 4, $xoopsDB->error());
            }
        }
    }

    function PRJSaveEdit($id, $title, $intro, $desc, $status, $priority, $pict, $original_pict, $noimage, $imagewhereto)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $title = $myts->addSlashes(str_replace("'", '’', $title));

        $intro = $myts->addSlashes(str_replace("'", '’', $intro));

        $desc = $myts->addSlashes(str_replace("'", '’', $desc));

        if (empty($pict) && 0 == $noimage) {
            $pict = $original_pict;
        } elseif (1 == $noimage) {
            $pict = '';
        }

        if (!empty($pict)) {
            if (true === uploadFile(XOOPS_ROOT_PATH . $xoopsModuleConfig['image_upload_dir'], explode(';', $xoopsModuleConfig['image_extension']), $xoopsModuleConfig['max_file_size'], $_POST['xoops_upload_file'][0])) {
            } else {
                // erreur dans le type du fichier uploadé

                redirect_header("adminprj.php?op=PRJEditProject&id=$id&title=$title&intro=$intro&desc=$desc&status=$status&priority=$priority&pict=$pict&imagewhereto=$imagewhereto", 4, sprintf(_AM_PRJ_ERRORUPLOADEDFILE, $xoopsModuleConfig['image_extension'], $xoopsModuleConfig['max_file_size']));
            }
        }

        $sql = 'UPDATE ' . $xoopsDB->prefix($module_tables[0]) . " SET title='$title', introduction='$intro', description='$desc', status=$status, priority=$priority, pict='$pict', imagewhereto=$imagewhereto WHERE ID_PROJECT=$id";

        $xoopsDB->query($sql);

        if (0 == $xoopsDB->errno()) {
            redirect_header('adminprj.php', 1, _AM_PRJ_DBUPDATED);
        } else {
            redirect_header('adminprj.php?', 4, $xoopsDB->error());
        }
    }

    function PRJDeleteProject($id)
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $sql = 'DELETE FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE ID_PROJECT=$id";

        $xoopsDB->query($sql);

        if (0 == $xoopsDB->errno()) {
            redirect_header('adminprj.php', 1, _AM_PRJ_DBUPDATED);
        } else {
            redirect_header('adminprj.php?', 4, $xoopsDB->error());
        }
    }

    function PRJAreYouSureDeleteProject()
    {
        global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

        $myts = MyTextSanitizer::getInstance();

        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $id = 0;
        }

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($module_tables[0]) . " WHERE ID_PROJECT=$id";

        $result = $xoopsDB->query($sql);

        $project = $xoopsDB->fetchArray($result);

        if (!empty($project['ID_PROJECT'])) {
            $sform = new XoopsThemeForm(_AM_PRJ_AREYOUSUREDELTE, 'delprj', xoops_getenv('PHP_SELF'));

            $sform->setExtra('enctype="multipart/form-data"');

            $sform->addElement(new XoopsFormLabel(_AM_PRJ_TITLE, $myts->displayTarea($project['title'])));

            $delete_button = new XoopsFormButton('', 'add', _AM_PRJ_DELETE, 'submit');

            $delete_button->setExtra("onmouseover='document.delprj.op.value=\"PRJDeleteProject\"'");

            $cancel_button = new XoopsFormButton('', 'add', _AM_PRJ_CANCEL, 'submit');

            $cancel_button->setExtra("onmouseover='document.delprj.op.value=\"PRJEditProject\"'");

            $button_tray = new XoopsFormElementTray('', '');

            $button_tray->addElement($delete_button);

            $button_tray->addElement($cancel_button);

            $sform->addElement($button_tray);

            $sform->addElement(new XoopsFormHidden('id', $id));

            $sform->addElement(new XoopsFormHidden('op', ''));

            $sform->display();
        }  

        // aucun projet, msg d'erreur
    }

    // ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';

    switch ($op) {
        case 'PRJAdmin':
            PRJAdmin();
            break;
        case 'PRJNewProject':
            PRJNewProject();
            break;
        case 'PRJShowProjects':
            PRJShowProjects();
            break;
        case 'PRNewProject':
            PRNewProject();
            break;
        case 'PRJEditProject':
            PRJEditProject();
            break;
        case 'PRJSaveAdd':
            PRJSaveAdd($title, $intro, $desc, $status_select, $priority, $HTTP_POST_FILES['pict']['name'], $imagewhereto_select);
            break;
        case 'PRJSaveEdit':
            if (empty($noimagechk)) {
                $noimagechk = 0;
            } else {
                $noimagechk = 1;
            }

            PRJSaveEdit($id, $title, $intro, $desc, $status_select, $priority, $HTTP_POST_FILES['pict']['name'], $original_pict, $noimagechk, $imagewhereto_select);
            break;
        case 'PRJDeleteProject':
            PRJDeleteProject($id);
            break;
        case 'PRJAreYouSureDeleteProject':
            PRJAreYouSureDeleteProject();
            break;
        default:
            PRJShowProjects();
            break;
    }

    // *************************** Fin de NTS **********************************

    buildPrjActionMenu();

    CloseTable();

    xoops_cp_footer();
