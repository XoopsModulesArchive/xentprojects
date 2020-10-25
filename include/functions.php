<?php

    require_once XOOPS_ROOT_PATH . '/modules/xentgen/include/xentfunctions.php';

    function makeStatusArray()
    {
        $arr = [];

        $arr[0] = _AM_PRJ_YES;

        $arr[1] = _AM_PRJ_NO;

        return $arr;
    }

    function makeImageDisplayedArray()
    {
        $arr = [];

        $arr[0] = _AM_PRJ_IMAGEWHERETODISPLAYEDOPT0;

        $arr[1] = _AM_PRJ_IMAGEWHERETODISPLAYEDOPT1;

        $arr[2] = _AM_PRJ_IMAGEWHERETODISPLAYEDOPT2;

        return $arr;
    }

    function buildPrjActionMenu()
    {
        echo "<br><div class='adminActionMenu'><a href=adminprj.php class='adminActionMenu'>" . _AM_PRJ_ADMINTITLE . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;';

        echo "<a href=adminprj.php?op=PRJNewProject class='adminActionMenu'>" . _AM_PRJ_NEWPROJECT . '</a></div>';
    }

/*	function makeSelect($caption="", $name, $selected, $arrayOptions, $linesDisplayed=1, $idMatters=0, $multiple=false){
        $select = new XoopsFormSelect($caption, $name, $selected, $linesDisplayed, $multiple);
        if ($idMatters == 1){
            $select->addOption(0, "---");
        }
        $select->addOptionArray($arrayOptions);
        return $select;
    }*/
