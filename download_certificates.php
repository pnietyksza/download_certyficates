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
    <br>
    <br>
    <span> Wpisuj identyfikatory postow z ktorych chcesz pobrac certyfikaty po przecinku(Na przyklad.: 1,2,3,4).
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


        if (is_object($post_object)) {
            $attachment_object = get_attached_media('', $id);
            if ($attachment_object) {
                $attachment_ids = array_keys($attachment_object);
                $attachment_urls = [];
                $attachment_names = [];
                $date = date('Y-m-d-H:i:s');
                $folder = WP_PLUGIN_DIR . '/download_certificates' . '/packages' . '/' . $date;
                foreach ($attachment_ids as $attachment_id) {
                    $attachment_url = wp_get_attachment_url((int) $attachment_id);
                    $attachment_title = get_the_title((int) $attachment_id);
                    array_push($attachment_names, $attachment_title);
                    array_push($attachment_urls, $attachment_url);
                }
                if ($attachment_urls) {
                    wp_mkdir_p($folder);
                }
                foreach ($attachment_urls as $key => $url) {
                    $file_name = basename($url);
                    if (file_put_contents($folder . '/' . $file_name, file_get_contents($url))) {

                    } else {
                        echo "File downloading failed.";
                    }
                }
                $files = scandir($folder);
                $zip = new ZipArchive();
                $filename = $folder . '/certyfikaty-' . $date . '.zip';

                if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
                    exit("cannot open <$filename>\n");
                }

                foreach ($files as $key => $value) {
                    if (
                        $value !== '.' &&
                        $value !== '..'
                    ) {
                        $zip->addFile($value);
                    }
                }


                $zip->close();


                $link = WP_PLUGIN_URL . '/download_certificates/packages' . '/' . $date . '/certyfikaty-' . $date . '.zip';
                echo '<a href=' . $link . '>Pobierz</a>';



            } else {
                echo 'Wpis o id: ' . $id . ' nie ma zalacznika.';
                exit;
            }
        } else {
            echo 'Wpis o id ' . $id . ' nie istnieje';
            exit;
        }


        $attachment_object = get_attached_media('', $id);
        $id_media_attachment = key($attachment_object);
        array_push($links_for_download, $attachment_object[(int) $id_media_attachment]->guid);
    }
    // var_dump(fopen($link,'a+'));
    // foreach ($links_for_download as $link) {
    //     file_put_contents(WP_PLUGIN_DIR . "/certificate_zip_generator/downloads/" . $id_media_attachment . ".pdf", fopen($link, 'r'));
    // }


}
