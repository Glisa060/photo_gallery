<?php
require_once '../../includes/initialize.php';


if(!$session->is_logged_in()) { redirect_to("login.php");}
?>

<?php include_layout_template('admin_header.php'); ?>

<h2>Menu</h2>

<hr/>

<a href="logfile.php">Log File</a> <br>
<a href="list_photos.php">List Photos</a> <br>
<a href="photo_upload.php">Photo Upload</a> <br>
<a href="comments.php">Comments Page</a>


<?php include_layout_template('admin_footer.php'); ?>

