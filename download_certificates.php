<?php


/**
 * @package download_certificates
 */
/*

Plugin Name: Download certificates

Plugin URI: https://github.com/pnietyksza/download_certyficates

Description:

Version: 1.0.0

Author: Patryk Nietyksza

Author URI: https://github.com/pnietyksza/

License:

Text Domain:

*/

add_action('admin_menu', 'certificate_zip_generator_init');

function certificate_zip_generator_init()
{
    add_menu_page('Test Plugin Page', 'Pobierz  certyfikaty', 'manage_options', 'test-plugin', 'main');
}

function main()
{

    ?>
    <script>
        console.log('Test wtyczki!');
    </script>
    <form action="admin.php?page=test-plugin" method="post">
        <input type="text" name="ids">
        <input type="submit" name="akcja" value="Pobierz certyfikaty" />
    </form>
    </br>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['akcja'])) {
        $ids = explode(',', $_POST['ids']);
        get_wanted_certs($ids);
    }
}

function get_wanted_certs(array $ids)
{
    if (!is_admin()) {
        require_once(ABSPATH . 'wp-admin/includes/post.php');
    }
    $links_for_download = [];
    foreach ($ids as $id) {
        $post_object = get_post($id);
        $attachment_object = get_attached_media('', $id);
        $id_media_attachment = key($attachment_object);
        array_push($links_for_download, $attachment_object[(int)$id_media_attachment]->guid);
    }
    // var_dump(fopen($link,'a+'));
    foreach ($links_for_download as $link) {
        file_put_contents(WP_PLUGIN_DIR . "/certificate_zip_generator/downloads/" . $id_media_attachment . ".pdf", fopen($link, 'r'));
    }



































//add_filter('manage_post_posts_columns', function($columns) {
//    return array_merge($columns, ['verified' => __('Verified', 'textdomain')]);
//});
//
//add_action('manage_post_posts_custom_column', function($column_key, $post_id) {
//    <form action="" meth>
//    </form>
//}, 10, 2);



//
//
//use MailPoetVendor\Symfony\Component\Validator\Mapping\Loader\XmlFileLoader;
//
//add_action('admin_menu', 'certificate_zip_generator_init');
//
//function certificate_zip_generator_init()
//{
//    add_menu_page('Test Plugin Page', 'Pobierz  certyfikaty', 'manage_options', 'test-plugin', 'main');
//}
//
//function main()
//{
//
//    ?>
<!--    <script>-->
<!--        console.log('Test wtyczki!');-->
<!--    </script>-->
<!--    <form action="admin.php?page=test-plugin" method="post">-->
<!--        <input type="text" name="ids">-->
<!--        <input type="submit" name="akcja" value="Zaaktualizuj produkty" />-->
<!--    </form>-->
<!--    </br>-->
<!--    --><?php
//    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['akcja'])) {
//        $ids = explode(',', $_POST['ids']);
//        get_wanted_certs($ids);
//    }
//}
//
//function get_wanted_certs(array $ids)
//{
//    if (!is_admin()) {
//        require_once(ABSPATH . 'wp-admin/includes/post.php');
//    }
//    $links_for_download = [];
//    foreach ($ids as $id) {
//        $post_object = get_post($id);
//        $attachment_object = get_attached_media('', $id);
//        $id_media_attachment = key($attachment_object);
//        array_push($links_for_download, $attachment_object[(int)$id_media_attachment]->guid);
//    }
//    // var_dump(fopen($link,'a+'));
//    foreach ($links_for_download as $link) {
//        file_put_contents(WP_PLUGIN_DIR . "/certificate_zip_generator/downloads/" . $id_media_attachment . ".pdf", fopen($link, 'r'));
//    }
//
//    exit;
//    $archive_name = "archive.zip"; // name of zip file
//    $archive_folder = WP_PLUGIN_DIR . "/certificate_zip_generator/downloads/"; // the folder which you archivate
//
//    $zip = new ZipArchive;
//    if ($zip -> open($archive_name, ZipArchive::CREATE) === TRUE)
//    {
//        $dir = preg_replace('/[\/]{2,}/', '/', $archive_folder."/");
//
//        $dirs = array($dir);
//        while (count($dirs))
//        {
//            $dir = current($dirs);
//            $zip -> addEmptyDir($dir);
//
//            $dh = opendir($dir);
//            while($file = readdir($dh))
//            {
//                if ($file != '.' && $file != '..')
//                {
//                    if (is_file($file))
//                        $zip -> addFile($dir.$file, $dir.$file);
//                    elseif (is_dir($file))
//                        $dirs[] = $dir.$file."/";
//                }
//            }
//            closedir($dh);
//            array_shift($dirs);
//        }
//
//        $zip -> close();
//        echo 'Archiving is sucessful!';
//    }
//    else
//    {
//        echo 'Error, can\'t create a zip file!';
//    }
//
//
//
//
//
//}