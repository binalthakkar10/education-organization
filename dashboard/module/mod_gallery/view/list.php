<?php
$galleryids = isset($_GET['gid']) ? $_GET['gid'] : "0";
if (isset($_POST['itf_datasid'], $_POST['itfactions'])) {
    $userids = isset($_POST['itf_datasid']) ? $_POST['itf_datasid'] : "0";
    $acts = $_POST['itfactions'];
    $ids = implode(',', $_POST['itf_datasid']);
    if ($acts == 'delete') {
        $objbanner->Gallery_deleteAdmin($ids);
        flash("Image is successfully deleted");
    }
    redirectUrl("itfmain.php?itfpage=" . $currentpagenames . "&gid=" . $galleryids);
}
$perpage = $stieinfo['paging_size'];
$urlpath = CreateLinkAdmin(array($currentpagenames)) . "&";
$galleryids = isset($_GET['gid']) ? $_GET['gid'] : "0";
$InfoData1 = $objbanner->showAllGallery($galleryids);
$pagingobj = new ITFPagination($urlpath, $perpage);
$InfoData = $pagingobj->setPaginateData($InfoData1);
$albumname = $objbanner->showAllAlbum();
?>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo $pagetitle; ?></div>
    <!-- Page Heading -->
    <div class="topcontroller"> 
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'add')); ?>" class="button add"><span>Upload Image </span></a> 
        <a onclick="return itfsubmitfrm('delete', 'itffrmlists');" class="button cancel"><span>Delete</span></a>
        <select name="gid" class="paymentType"  id="gid">
            <option value="">--Please select gallery--</option>
            <?php foreach ($albumname as $album) {
                ?>
                <option value="<?php echo $album['id'] ?>" <?php
                if ($galleryids == $album['id']) {
                    echo'selected="selected"';
                }
                ?>>
                            <?php echo $album['album_name']; ?>
                </option>
            <?php } ?>
        </select>  
    </div>
    <div class="clear"></div>
    <form id="itffrmlists" name="itffrmlists" method="post" action="">
        <input type="hidden" name="itfactions" id="itfactions" value="" />
        <input type="hidden" name="itf_status" id="itf_status" value="" />
        <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <th width="4%" align="center" ><input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>

                <th width="21%" align="center"  >Image Title</th>
              <!-- <th width="21%" align="center"  >Image Desc</th>-->
                <th width="57%" align="left"  >Images </th>
               <!-- <th width="57%" align="left"  >Gallery Name</th>-->
                <th width="9%"  align="center"  >Action</th>
            </tr>
            <?php
            if (count($InfoData) > 0) {
                foreach ($InfoData as $k => $itfdata) {
                    ?>
                    <tr class="<?php echo ($k % 2 == 0) ? "rowsfirst" : "rowssec"; ?>" >
                        <td align="left"><input name="itf_datasid[]" type="checkbox" value="<?php echo $itfdata['id']; ?>" class="itflistdatas" /></td>
                        <td  align="left" ><?php echo $itfdata['name']; ?></td>   
                        <td align="left" class=""><img src="<?php echo PUBLICPATH . "image_gallery/" . $itfdata['album_id'] . "/" . $itfdata['image_name']; ?>" width="25" height="25"  /></td>
                        <td align="center"><a href="<?php echo CreateLinkAdmin(array($currentpagenames, 'actions' => 'edit', 'id' => $itfdata['id'])); ?>" title="Edit Category " alt="Edit Category"><img src="img/i_edit.png" border="0" /></a> </td>
                         <!--<td  align="left" ><?php echo $itfdata['album_id']; ?></td> -->
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="10" class="align-center">No Record Available !</td>
                </tr>
            <?php } ?>
        </table>
    </form>		<!-- Pagging -->
    <div class="pagging">
        <div class="right">
            <?php echo $pagingobj->Pages(); ?>
        </div>
    </div>
    <!-- End Pagging -->

</div>
<script>
    $(document).ready(function() {

        $("select#gid").change(function() {

            var gid = $(this).val();

            window.location = '<?php echo ITFPATH; ?>' + "admin/itfmain.php?itfpage=gallery&gid=" + gid;

        })


    });

</script>
