<?php defined('ABSPATH') or die('No script kiddies please!');

class Feature_Image
{
    private $postId;
    private $postTitle;

    public function __construct($post_id, $post_title)
    {
        $this->postId =  $post_id;
        $this->postTitle =  $post_title;
    }

    public function run(): string | bool
    {
        require(DAILYOMENS_PLUGINS . 'I18N/Persian.php');
        require(DAILYOMENS_UTILS . 'helpers.php');

        try {
            $arabic = new I18N_Arabic_Glyphs('Glyphs');
            $font = DAILYOMENS_ROOTDIR . 'site/static/fonts/IRANSans.ttf';
            $txt = $arabic->utf8Glyphs(mb_convert_encoding($this->postTitle, "UTF-8"));
            // Image settings
            $dayOfMonth = getCurrentShamsiDate('d', null);
            $remainder  = intval($dayOfMonth) % 3;
            $featuredImageName = '';
            switch ($remainder) {
                case 0:
                    $featuredImageName = 'featured_image_2.jpg';
                    break;
                case 1:
                    $featuredImageName = 'featured_image_1.jpg';
                    break;
                case 2:
                    $featuredImageName = 'featured_image_3.jpg';
                    break;
                default:
                    break;
            }
            $img = imagecreatefromjpeg(DAILYOMENS_ROOTDIR . 'site/static/images/featured_images/simple_omen/' . $featuredImageName);
            $fontSize = 44;
            $fontColor = imagecolorallocate($img, 255, 255, 255);
            $posX = 5;
            $posY = 24;
            $angle = 0;
            $iWidth = imagesx($img);
            $iHeight = imagesy($img);
            $tSize = imagettfbbox($fontSize, $angle, $font, $txt);
            $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
            $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);
            $centerX = ceil(($iWidth - $tWidth) / 2);
            $centerX = $centerX < 0 ? 0 : $centerX;
            $centerY = ceil(($iHeight - $tHeight) / 2) + 36;
            $centerY = $centerY < 0 ? 0 : $centerY;

            imagettftext($img, $fontSize, $angle, $centerX, $centerY, $fontColor, $font, $txt);

            $quality = 100;
            $imagePath = DAILYOMENS_ROOTDIR . sprintf('site/static/images/featured_images/simple_omen/%s-tempImg.jpg', time());
            imagejpeg($img, $imagePath, $quality);
            // Add featured image to post
            $imageName       = sprintf('simpleOmen-%s.jpg', time());
            $uploadDir       = wp_upload_dir();
            $imageData       = file_get_contents($imagePath);
            $uniqueFileName = wp_unique_filename($uploadDir['path'], $imageName);
            $fileName         = basename($uniqueFileName);
            $file = wp_mkdir_p($uploadDir['path'])
                ? $uploadDir['path'] . '/' . $fileName
                : $uploadDir['basedir'] . '/' . $fileName;
            file_put_contents($file, $imageData);
            $wpFileType = wp_check_filetype($fileName, null);
            $attachment = array(
                'post_mime_type' => $wpFileType['type'],
                'post_title'     => sanitize_file_name($fileName),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            $attachmentId = wp_insert_attachment($attachment, $file, $this->postId);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachData = wp_generate_attachment_metadata($attachmentId, $file);
            wp_update_attachment_metadata($attachmentId, $attachData);
            @unlink($imagePath);
            return strval($attachmentId);
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
}
