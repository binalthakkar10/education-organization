<?php
if (empty($_SESSION['teacherLoginInfo'])) {

    redirectUrl(CreateLink(array("teacherlogin")));
    exit;
}
?><div class="left_sidebar">
    <div id="picture_gallery">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher">Active Class</a></li>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&type=1&future=yes">Future Classes</a></li>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&type=2">Active Summer Camps</a></li>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&type=2&future=yes">Future Summer Camps</a></li>
            <?php if(!empty($_SESSION['teacherLoginInfo']['url'])) : ?>
            <li><a target="_blank" href="<?php echo $_SESSION['teacherLoginInfo']['url'] ?>">Paychecks and Rosters</a></li>
            <?php endif; ?>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=contents&itemid=policies">Policies and Procedures</a></li>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=contents&itemid=resources">Teacher Resources</a></li>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=contents&itemid=directory">Company Directory</a></li>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=contents&itemid=newsletters">Newsletter Archives</a></li>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=changepassword">Change Password</a></li>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=teacher_profile">My Profile</a></li>
            <li><a href="<?php echo SITEURL ?>/index.php?itfpage=teacher&itemid=teacherlogout">Logout</a></li>
        </ul>
    </div>
</div><!-- left_sidebar -->