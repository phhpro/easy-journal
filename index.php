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
 ***********************************************************************
 *                                                   BEGIN USER CONFIG *
 ***********************************************************************
 */


/**
 * Document root
 *
 * Full path without trailing / if $_SERVER has wrong value.
 * Example: $path = "/home/john/htdocs";
 */
$path = $_SERVER['DOCUMENT_ROOT'];


/**
 * Script folder
 * Data folder
 * Editor token
 */
$fold = "/easy-journal";
$data = "/data";
$edit = "edit";


/**
 * Blog name and META
 */
$name = "PHP Easy Journal";
$mdes = "PHP Easy Journal free PHP micro blogging script by phhpro";
$mkey = "PHP Easy Journal";


/**
 * Default index and language
 */
$indx = "index.php";
$lang = "en";


/**
 ***********************************************************************
 *                                                     END USER CONFIG *
 ***********************************************************************
 */


//** Script version
$make = "20180902";

//** Check protocol
if (isset($_SERVER['HTTPS']) && "on" === $_SERVER['HTTPS']) {
    $prot = "s";
} else {
    $prot = "";
}

$prot = "http" . $prot . "://";

/**
 * Host
 * Script path
 */
$host = $_SERVER['HTTP_HOST'];
$home = $path . $fold;

/**
 * Data year
 * Data file
 */
$year = $home . $data . "/" . date('Y');
$file = date('m') . ".html";

/**
 * Save location
 * Post location
 */
$save = $year . "/" . $file;
$post = $prot . $host . $_SERVER['SCRIPT_NAME'] . "?" . $edit;

//** Status
$stat = "";

//** Check mode
if (isset($_GET[$edit])) {
    $mode = " - Editor";
} else {
    $mode = "";
}

//** Begin mark-up
echo "<!DOCTYPE html>\n" .
     "<html lang=\"$lang\">\n" .
     "    <head>\n" .
     "        <meta charset=\"UTF-8\"/>\n" .
     "        <meta name=language content=\"$lang\"/>\n" .
     "        <meta name=viewport content=\"width=device-width, " .
     "height=device-height, initial-scale=1\"/>\n" .
     "        <meta name=description content=\"$mdes\"/>\n" .
     "        <meta name=keywords content=\"$mkey\"/>\n" .
     '        <meta name=robots content="noodp, noydir"/>' . "\n" .
     "        <title>$name$mode</title>\n" .
     "        <style>\n" .
     "        * {\n" .
     "            font-family: sans-serif;\n" .
     "        }\n" .
     "        input, textarea {\n" .
     "            width: 50.5%;\n" .
     "            font-size: 90%;\n" .
     "        }\n" .
     "        input[type=submit] {\n" .
     "            width: 25%;\n" .
     "            font-weight: bold;\n" .
     "        }\n" .
     "        .text {\n" .
     "            white-space: pre-wrap;\n" .
     "            word-wrap: break-word;\n" .
     "            border-bottom: 1px solid #ccc;\n" .
     "        }\n" .
     "        </style>\n" .
     "    </head>\n" .
     "    <body>\n" .
     "        <h1>$name$mode</h1>\n";

//** Check editor query
if (isset($_GET[$edit])) {

    //** Quit editor
    if (isset($_POST['quit'])) {
        header("Location: $prot$host$fold");
        exit;
    }

    /**
     * WARNING: Unfiltered POST data is a potential security risk!
     *
     * While the script assumes you are the only one with access
     * to the editor token, even a basic htmlentities() wrapper
     * can improve security -- at the cost of text-only entries.
    */
/*
    $head = htmlentities($_POST['head']);
    $text = htmlentities($_POST['text']);
*/
    $head = $_POST['head'];
    $text = $_POST['text'];

    //** Post entry
    if (isset($_POST['post'])) {

        //** Check values and build entry
        if ($head ===  "") {
            $stat = "Missing header!";
        } elseif ($text === "") {
            $stat = "Missing text!";
        } else {
            $body = "        <div class=head>" . date('Y-m-d H:i:s') .
                    " <strong>$head</strong></div>\n" .
                    "        <p class=text>$text</p>\n";

            //** Check primary data folder
            if (!is_dir("$home$data")) {

                if (mkdir("$home$data") === false) {
                    echo "Failed to create archives folder!";
                    exit;
                } else {
                    mkdir("$home$data");
                }
            }

            //** Check secondary data folder
            if (!is_dir($year)) {

                if (mkdir($year) === false) {
                    echo "Failed to create data folder!";
                    exit;
                } else {
                    mkdir($year);
                }
            }

            //** Read existing data file
            if (is_file($save)) {
                $body .= file_get_contents($save);
            }

            //** Save entry
            file_put_contents($save, $body);
            header("Location: $post");
            exit;
        }
    }

    //** Check data file
    if (is_file($save)) {
        $newd = file_get_contents($save);
    } else {
        $newd = "";
        $stat = "No current entries.";
    }

    //** Add new entry
    if (isset($_POST['update'])) {
        /**
         * No filter to keep sources intact
         * No exit to keep editor open
         */
        if ($newd !== "") {
            $newd = $_POST['update_data'];
            file_put_contents($save, $newd);
            header("Location: $post");
        }
    }

    echo "        <form action=\"#\" method=POST " .
         "accept-charset=\"UTF-8\">\n" .
         "        <h2>New</h2>\n" .
         "        <p>$stat</p>\n" .
         "            <p><label for=head>Head</label></p>\n" .
         "            <textarea name=head id=head rows=2 cols=80 " .
         "title=\"Type here to enter the entry's title text\">";

    if (isset($head)) {
        echo $head;
    }

    echo "</textarea>\n" .
         "            <p><label for=text>Text</label></p>\n" .
         "            <textarea name=text id=text rows=8 cols=80 " .
         "title=\"Type here to enter the entry's body text\">";

    if (isset($text)) {
        echo $text;
    }

    echo "</textarea>\n" .
         "            <p>\n" .
         "                <input " .
         "type=submit name=post value=\"Post\" " .
         "title=\"Click here to post a new entry\"/>\n" .
         "                <input " .
         "type=submit name=quit value=\"Quit\" " .
         "title=\"Click here to quit the editor\"/>\n" .

         "            </p>\n" .

         //** Edit old entry
         "            <h2>Old</h2>\n" .
         "            <p><label for=update_data>Data</label></p>\n" .
         "            <textarea name=update_data id=update_data " .
         "rows=8 cols=80 title=\"Type here to edit old entries\">" .
         "$newd</textarea>\n" .
         "            <p>\n" .
         "                <input " .
         "type=submit name=update value=\"Update\" " .
         "title=\"Click here to update modified old entries\"/>\n" .
         "                <input " .
         "type=submit name=quit value=\"Quit\" " .
         "title=\"Click here to quit the editor\"/>\n" .
         "            </p>\n" .
         "        </form>\n";
} else {
    //** Check data file
    $view = $save;

    if (!is_file($save)) {
        $stat = "No current entries.";
    }

    //** Print status and trim path
    echo "        <p>$stat</p>\n";
    $view = str_replace($prot . $host, $path, $view);

    //** Load selected data file
    if (is_file($view)) {
        echo file_get_contents($view);
    }

    //** Check and parse data folder
    if (is_dir("$home$data")) {
        echo "        <h2>Data</h2>\n";
        chdir("." . $data);
        $dirs = glob("*", GLOB_ONLYDIR);

        foreach ($dirs as $dir) {
            echo "        <ul>\n" .
                 "            <li><strong>$dir</strong>\n" .
                 "                <ul>\n";
            $scan = glob("$dir/*.html");

            foreach ($scan as $file) {
                $list = str_replace($dir . "/", "", $file);
                $list = str_replace(".html", "", $list);

                echo "                    <li>" .
                     "<a href=\"$prot$host$fold$data/$file\" " .
                     "title=\"View data file " .
                     str_replace(".html", "", $file) . "\" " .
                     "class=ext>$list</a></li>\n";
            }

            unset($file);
            echo "                </ul>\n";
        }

        unset($dir);
        echo "            </li>\n" .
             "        </ul>\n";
    } else {
        $stat = "No current entries.";
    }
}

/**
 * End mark-up
 *
 * Please keep the reference link intact.
 * Others may find the script useful too, thank you.
 */
echo "        <p>&copy; " . date('Y') . " " . $host . " - " .
     "All rights reserved</p>\n" .
     '        <p>Powered by ' .
     '<a href="https://github.com/phhpro/easy-journal" ' .
     'title="Click here to get a free copy of this script">' .
     "PHP Easy Journal v$make</a></p>\n" .
     '        <script>var a=document.getElementsByTagName("a");' .
     'for(var i in a){if(a[i].className&&a[i].className.indexOf(' .
     '"ext") !=-1){a[i].onclick=function(){return !window.open(' .
     'this);}}}</script>' . "\n" .
     "    </body>\n" .
     "</html>\n";
