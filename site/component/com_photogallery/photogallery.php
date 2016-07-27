<?php
$obj = new CreateAlbum;
$albumResult = $obj->showAllActiveAlbum();
if ($_GET['album_id'] != '') {
    $albumId = $_GET['album_id'];
} else {
    $albumId = $albumResult[0]['id'];
}
if ($albumId) {
    $galleryResult = $obj->getAllAlbumImages($albumId);
}
$currentUrl = $_SERVER['REQUEST_URI'];
$relative_path = $_SERVER['PHP_SELF'];
//echo SITEPATH;
?>
<style>
    #gallery ul li img {
        height: 152px;
        width: 216px;
    }
</style>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <div id="page_title">
            <h1>Picture <span style="color:#ab281f;">Gallery</span></h1>
        </div>
        <div id="page_content">
            <div class="left_sidebar">
                <div id="picture_gallery">
                    <h2>Picture Album</h2>
                    <ul>
                        <?php
                        foreach ($albumResult as $values) {
                            echo '<li><a href="' . $currentUrl . '&album_id=' . $values[id] . '">' . $values[album_name] . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div><!-- left_sidebar -->
            <div class="right_content">
                <div id="gallery">
                    <ul>
                        <?php
                        if (count($galleryResult) > 0) {
                            foreach ($galleryResult as $values) {

                                echo '<li><a class="fancybox" href="' . PUBLICPATH . 'image_gallery/' . $values[album_id] . '/' . $values[image_name] . '" data-fancybox-group="gallery" title="" ><img src="' . PUBLICPATH . '/image_gallery/' . $values[album_id] . '/' . $values[image_name] . '" /></a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div><!-- right_content -->
        </div>
    </div>
</div>
<link href="<?php echo SITEURL ?>/template/default/css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo SITEURL ?>/template/default/js/jquery.fancybox.pack.js"></script>

<script type="text/javascript">
    
    
    $(document).ready(function() {
        $('.fancybox').fancybox();
    });
</script>
