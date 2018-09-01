<?php
/**
 * PHP Version 5 and above
 *
 * Minimalist blogging script
 *
 * @category  PHP_Microblogging
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
 * Archives folder
 * Editor token
 */
$fold = "/easy-journal";
$arch = "/archives";
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
$make = 20180901;

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
 * Data path
 * Data type
 * Data file
 * Save location
 * Post location
 * Status
 */
$host = $_SERVER['HTTP_HOST'];
$home = $path . $fold;
$data = $home . $arch . "/" . date('Y');
$type = "html";
$item = date('m') . "." . $type;
$save = $data . "/" . $item;
$post = $prot . $host . $_SERVER['SCRIPT_NAME'] . "?" . $edit;
$stat = "";

//** Begin mark-up
echo "<!DOCTYPE html>\n" .
     '<html lang="' . $lang . '">' . "\n" .
     "    <head>\n" .
     '        <meta charset="UTF-8"/>' . "\n" .
     '        <meta name=language content="' . $lang . '"/>' . "\n" .
     '        <meta name=viewport content="width=device-width, ' .
     'height=device-height, initial-scale=1"/>' . "\n" .
     '        <meta name=description content="' . $mdes . '"/>' . "\n" .
     '        <meta name=keywords content="' . $mkey . '"/>' . "\n" .
     '        <meta name=robots content="noodp, noydir"/>' . "\n" .
     "        <title>$name</title>\n" .
     "        <style>\n" .
     "        * {\n" .
     "            font-family: sans-serif;\n" .
     "        }\n" .
     "        input, textarea {\n" .
     "            width: 50%;\n" .
     "        }\n" .
     "        .text {\n" .
     "            text-align: justify;\n" .
     "            white-space: pre-wrap;\n" .
     "            word-wrap: break-word;\n" .
     "            border-bottom: 1px solid #ccc;\n" .
     "        }\n" .
     "        </style>\n" .
     "    </head>\n" .
     "    <body>\n" .
     "        <h1>$name</h1>\n";

//** Check editor query
if (isset($_GET[$edit])) {

    //** Quit editor
    if (isset($_POST['quit'])) {
        header("Location: $prot$host$fold");
        exit;
    }

    //** Post entry
    if (isset($_POST['post'])) {

        //** Check values and build entry
        if ($_POST['head'] ===  "") {
            $stat = "Missing header!";
        } elseif ($_POST['text'] === "") {
            $stat = "Missing text!";
        } else {
            $body = "        <div class=head>" . date('Y-m-d H:i:s') .
                    " <strong>" . $_POST['head'] . "</strong></div>\n" .
                    "        <p class=text>" . $_POST['text'] . "</p>\n";

            //** Check archives folder
            if (!is_dir($home . $arch)) {

                if (mkdir($home . $arch) === false) {
                    echo "Failed to create archives folder!";
                    exit;
                } else {
                    mkdir($home . $arch);
                }
            }

            //** Check data folder
            if (!is_dir($data)) {

                if (mkdir($data) === false) {
                    echo "Failed to create data folder!";
                    exit;
                } else {
                    mkdir($data);
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
        $new = file_get_contents($save);
    } else {
        $new  = "";
        $stat = "No current entries.";
    }

    //** Add new entry
    if (isset($_POST['update'])) {
        /**
         * No filter to keep sources intact
         * No exit to keep editor open
         */
        if ($new !== "") {
            $new = $_POST['update_data'];
            file_put_contents($save, $new);
            header("Location: $post");
        }
    }

    echo "        <p><strong>Add new entry</strong></p>\n" .
         "        <p>$stat</p>\n" .
         '        <form action="#" method=POST ' .
         'accept-charset="UTF-8">' . "\n" .
         "            <p><label for=head>Header</label></p>\n" .
         "            <textarea name=head id=head " .
         'rows=3 cols=80 title="Type here to enter the header text">';

    if (isset($_POST['head'])) {
        echo $_POST['head'];
    }

    echo "</textarea>\n" .
         "            <p><label for=text>Text</label></p>\n" .
         "            <textarea name=text id=text rows=10 cols=80 " .
         'title="Type here to enter text of new entry">';

    if (isset($_POST['text'])) {
        echo $_POST['txt'];
    }

    echo "</textarea>\n" .
         "            <p>\n" .
         "                <input type=submit name=post " .
         'value="Add" title="Click here to add a new entry"/>' . "\n" .
         "            </p>\n" .

         //** Update old entries
         "            <p><strong>Update old entries</strong></p>\n" .
         "            <p><label for=update_data>Data</label></p>\n" .
         "            <textarea name=update_data id=update_data " .
         'rows=10 cols=80 title="Type here to edit old entries">' .
         "$new</textarea>\n" .
         "            <p>\n" .
         "                <input type=submit name=update " .
         'value="Update" ' .
         'title="Click here to update the data file"/>' . "\n" .
         "            </p>\n" .

         //** Quit editor
         "            <p>\n" .
         "                <input type=submit name=quit " .
         'value="Quit" title="Click here to quit the editor"/>' . "\n" .
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

    //** Check and parse archives folder
    if (is_dir($home . $arch)) {
        echo "        <p><strong>Archives</strong></p>\n";
        chdir("." . $arch);
        $dirs = glob("*", GLOB_ONLYDIR);

        foreach ($dirs as $dir) {
            echo "        <ul><strong>$dir</strong>\n";
            $scan = glob("$dir/*.$type");

            foreach ($scan as $item) {
                $list = str_replace($dir . "/", "", $item);
                $list = str_replace('.' . $type, "", $list);

                echo '            <li><a href="' . $prot . $host .
                     $fold . $arch . "/" . $item . '" ' .
                     'title="Browse archive ' .
                     str_replace("." . $type, "", $item) . '" ' .
                     "class=ext>$list</a></li>\n";
            }

            unset($item);
            echo "        </ul>\n";
        }

        unset($arch);
    } else {
        $stat = "No current entries.";
    }
}

//** End mark-up
echo "        <p>&copy; " . date('Y') . " " . $name . " - " .
     "All rights reserved</p>\n" .
     '        <p>Powered by ' .
     '<a href="https://github.com/phhpro/easy-journal" ' .
     'title="Click here to get a free copy of this script">' .
     "PHP Easy Journal v$make</a></p>\n" .
     '            <script>var a=document.getElementsByTagName("a");' .
     'for(var i in a){if(a[i].className&&a[i].className.indexOf(' .
     '"ext") !=-1){a[i].onclick=function(){return !window.open(' .
     'this);}}}</script>' . "\n" .
     "    </body>\n" .
     "</html>\n";
