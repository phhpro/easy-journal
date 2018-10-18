<?php
/**
 * PHP Version 5 and above
 *
 * Minimalist blogging script
 *
 * @category  PHP_Blogging
 * @package   PHP_Easy_Journal
 * @author    P H Claus <phhpro@gmail.com>
 * @copyright 2015 - 2018 P H Claus
 * @license   https://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @version   GIT: Latest
 * @link      https://github.com/phhpro/easy-journal
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 */


/**
 * *********************************************************************
 *                                                   BEGIN USER CONFIG *
 * *********************************************************************
 */


/**
 * Document root
 *
 * Full path without trailing / if $_SERVER has wrong value.
 * Example: $ejn_path = "/home/john/htdocs";
 */
$ejn_path = $_SERVER['DOCUMENT_ROOT'];


/**
 * Script and data folder -- without trailing /
 */
$ejn_fold = "/easy-journal";
$ejn_data = "/data";

/**
 * Editor token to invoke editor screen, e.g. /easy-journal/?edit
 * Frontend language -- must match language file (without extension)
 */
$ejn_edit = "edit";
$ejn_lang = "en";


/**
 * Blog title
 * META description
 * META keywords
 */
$ejn_blog = "John's blogs of cats and dogs";
$ejn_mdes = "John's personal blogs about cats and dogs and whatnot.";
$ejn_mkey = "john doe,blogs,cats,dogs";


/**
 * *********************************************************************
 *                                                     END USER CONFIG *
 * *********************************************************************
 */


//** Script version
$ejn_make = "20181018";

//** Check protocol
if (isset($_SERVER['HTTPS']) && "on" === $_SERVER['HTTPS']) {
    $ejn_prot = "s";
} else {
    $ejn_prot = "";
}

$ejn_prot = "http$ejn_prot://";

//** Host and path
$ejn_host = $_SERVER['HTTP_HOST'];
$ejn_home = "$ejn_path$ejn_fold";

//** Content folder and data file
$ejn_cont = "$ejn_home$ejn_data/" . date('Y');
$ejn_file = date('m') . ".html";

//** Save and post location
$ejn_save = "$ejn_cont/$ejn_file";
$ejn_post = "$ejn_prot$ejn_host" . $_SERVER['SCRIPT_NAME'] . "?$ejn_edit";

//** Language data file
$ejn_ldat = "$ejn_home/lang/$ejn_lang.php";

if (file_exists($ejn_ldat)) {
    include $ejn_ldat;
} else {
    echo "Missing or invalid language file! Script halted.";
    exit;
}


//** Language file OK -- localised strings now available


//** Check mode
if (isset($_GET[$ejn_edit])) {
    $ejn_mode = " - " . $ejn_lstr['editor'];
} else {
    $ejn_mode = "";
}

//** Header
echo "<!DOCTYPE html>\n" .
     "<html lang=\"$ejn_lang\">\n" .
     "    <head>\n" .
     "        <meta charset=\"UTF-8\"/>\n" .
     "        <meta name=language content=\"$ejn_lang\"/>\n" .
     "        <meta name=viewport content=\"width=device-width, " .
     "height=device-height, initial-scale=1\"/>\n" .
     "        <meta name=description " .
     "content=\"PHP Easy Journal " .
     "free PHP micro blogging script by phhpro. $ejn_mdes\"/>\n" .
     "        <meta name=keywords " .
     "content=\"PHP Easy Journal,$ejn_mkey\"/>\n" .
     "        <meta name=robots content=\"noodp, noydir\"/>\n" .
     "        <link rel=stylesheet " .
     "href=\"$ejn_prot$ejn_host$ejn_fold/style.css\">\n" .
     "        <title>$ejn_blog$ejn_mode</title>\n" .
     "    </head>\n" .
     "    <body>\n" .
     "        <h1>$ejn_blog$ejn_mode</h1>\n";

//** Check editor query
if (isset($_GET[$ejn_edit])) {

    //** Quit editor
    if (isset($_POST['ejn_quit'])) {
        header("Location: $ejn_prot$ejn_host$ejn_fold");
        exit;
    }

    /**
     * *****************************************************************
     * WARNING -- Unfiltered POST data is a potential security risk!   *
     *                                                                 *
     * See below how to sanitise data using htmlentities()             *
     *                                                                 *
     * However, this will completely disable all mark-up, so no more   *
     * formatting, images, etc. Your posts will be stricly text only.  *
     *                                                                 *
     * $ejn_head = htmlentities($_POST['head']);                           *
     * $ejn_text = htmlentities($_POST['text']);                           *
     * *****************************************************************
    */
    $ejn_head = $_POST['ejn_head'];
    $ejn_text = $_POST['ejn_text'];

    //** Post entry
    if (isset($_POST['ejn_post'])) {

        //** Check values and build entry
        if ($ejn_head ===  "") {
            echo "        <p class=ejn_info>" .
                 $ejn_lstr['head_miss'] . "</p>\n";
        } elseif ($ejn_text === "") {
            echo "        <p class=ejn_info>" .
                 $ejn_lstr['text_miss'] . "</p>\n";
        } else {
            $ejn_body = "        <div class=ejn_item>\n" .
                        "            <div class=ejn_head>" .
                        date('Y-m-d H:i:s') .
                        " <strong>$ejn_head</strong></div>\n" .
                        "            <div class=ejn_text>$ejn_text</div>\n" .
                        "        </div>\n";

            //** Check data folder
            if (!is_dir("$ejn_home$ejn_data")) {

                if (mkdir("$ejn_home$ejn_data") === false) {
                    echo $ejn_lstr['data_fail'];
                    exit;
                } else {
                    mkdir("$ejn_home$ejn_data");
                }
            }

            //** Check content folder
            if (!is_dir($ejn_cont)) {

                if (mkdir($ejn_cont) === false) {
                    echo $ejn_lstr['cont_fail'];
                    exit;
                } else {
                    mkdir($ejn_cont);
                }
            }

            //** Read existing data file
            if (is_file($ejn_save)) {
                $ejn_body .= file_get_contents($ejn_save);
            }

            //** Save entry
            file_put_contents($ejn_save, $ejn_body);
            header("Location: $ejn_post");
            exit;
        }
    }

    //** Check data file
    if (is_file($ejn_save)) {
        $ejn_newd = file_get_contents($ejn_save);
    } else {
        $ejn_newd = "";
    }

    //** Check if posting update
    if (isset($_POST['ejn_update'])) {
        /**
         * No filter to keep sources intact
         * No exit to keep editor open
         */
        if ($ejn_newd !== "") {
            $ejn_newd = $_POST['ejn_update_data'];
            file_put_contents($ejn_save, $ejn_newd);
            header("Location: $ejn_post");
        }
    }

    //** Open editor form
    echo "        <form action=\"#\" " .
         "method=POST accept-charset=\"UTF-8\">\n" .
         "        <h2>" . $ejn_lstr['new'] . "</h2>\n" .

    //** Entry title
         "            <p><label for=ejn_head>" .
         $ejn_lstr['head'] . "</label></p>\n" .
         "            <textarea " .
         "name=ejn_head id=ejn_head rows=2 cols=80 " .
         "title=\"" . $ejn_lstr['head_tip'] . "\">";

    if (isset($ejn_head)) {
        echo $ejn_head;
    }

    echo "</textarea>\n" .

    //** Entry text
         "            <p><label for=ejn_text>" .
         $ejn_lstr['text'] . "</label></p>\n" .
         "            <textarea " .
         "name=ejn_text id=ejn_text rows=8 cols=80 " .
         "title=\"" . $ejn_lstr['text_tip'] . "\">";

    if (isset($ejn_text)) {
        echo $ejn_text;
    }

    echo "</textarea>\n" .

    //** Post new entry
         "            <p>\n" .
         "                <input type=submit name=ejn_post " .
         "value=\"" . $ejn_lstr['post'] . "\" " .
         "title=\"" . $ejn_lstr['post_tip'] . "\"/>\n" .
         "                <input type=submit name=ejn_quit " .
         "value=\"" . $ejn_lstr['quit'] . "\" " .
         "title=\"" . $ejn_lstr['quit_tip'] . "\"/>\n" .
         "            </p>\n" .

    //** Edit old entry
         "            <h2>" . $ejn_lstr['old'] . "</h2>\n" .
         "            <p><label for=ejn_update_data>" .
         $ejn_lstr['data'] . "</label></p>\n" .
         "            <textarea " .
         "name=ejn_update_data ejn_id=update_data rows=8 cols=80 " .
         "title=\"" . $ejn_lstr['old_tip'] . "\">$ejn_newd" .
         "</textarea>\n" .
         "            <p>\n" .

    //** Update old entry
         "                <input type=submit name=ejn_update " .
         "value=\"" . $ejn_lstr['update'] . "\" " .
         "title=\"" . $ejn_lstr['update_tip'] . "\"/>\n" .
         "                <input type=submit name=ejn_quit " .
         "value=\"" . $ejn_lstr['quit'] . "\" " .
         "title=\"" . $ejn_lstr['quit_tip'] . "\"/>\n" .
         "            </p>\n" .
         "        </form>\n";
} else {
    //** Check data folder
    if (!is_dir("$ejn_home$ejn_data")) {
        echo "        <p>" . $ejn_lstr['nothing'] . "</p>\n";
    } else {
        //** Trim path
        $view = str_replace("$ejn_prot$ejn_host", $ejn_path, $ejn_save);

        //** View data file
        if (is_file($view)) {
            echo file_get_contents($view);
        }

        //** Parse data folder
        echo "        <h2>" . $ejn_lstr['archives'] . "</h2>\n";
        chdir(".$ejn_data");
        $ejn_pdir = glob("*", GLOB_ONLYDIR);

        foreach ($ejn_pdir as $ejn_cdir) {
            echo "        <ul>\n" .
                 "            <li><strong>$ejn_cdir</strong>\n" .
                 "                <ul>\n";
            $ejn_scan = glob("$ejn_cdir/*.html");

            foreach ($ejn_scan as $ejn_file) {
                $ejn_list = str_replace("$ejn_cdir/", "", $ejn_file);
                $ejn_list = str_replace(".html", "", $ejn_list);

                echo "                    <li>" .
                     "<a href=\"" .
                     "$ejn_prot$ejn_host$ejn_fold$ejn_data/$ejn_file\" " .
                     "title=\"" . $ejn_lstr['view_tip'] . " " .
                     str_replace(".html", "", $ejn_file) . "\" " .
                     "class=ejn_ext>$ejn_list</a></li>\n";
            }

            unset($ejn_file);
            echo "                </ul>\n";
        }

        unset($ejn_cdir);
        echo "            </li>\n" .
             "        </ul>\n";
    }
}

/**
 * Footer
 *
 * GPL v3 -- Please keep the script reference intact.
 * Others may also find this useful.
 */
echo "        <p>&copy; " . date('Y') . " $ejn_host - " .
     $ejn_lstr['rights'] . "</p>\n" .
     "        <p id=ejn_by>" . $ejn_lstr['by'] . " " .
     "<a href=\"https://github.com/phhpro/easy-journal\" " .
     "title=\"" . $ejn_lstr['get_tip'] . "\" " .
     "class=ejn_ext>PHP Easy Journal v$ejn_make</a></p>\n" .
     "        <script>var a=document.getElementsByTagName('a');" .
     "for(var i in a){if(a[i].className&&" .
     "a[i].className.indexOf('ejn_ext') !=-1){a[i].onclick=function(){" .
     "return !window.open(this);}}}</script>\n" .
     "    </body>\n" .
     "</html>\n";
