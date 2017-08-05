<?php
require_once '../../includes/initialize.php';
if(!$session->is_logged_in()) { redirect_to("login.php");}
?>

<?php
$max_file_size = 1048576;

if (isset($_POST['submit']))
{
    $photo = new Photograph();
    $photo->caption = $_POST['caption'];
    $photo->attach_file($_FILES['file_upload']);
    if ($photo->save())
    {
        $session->message("The file has been uploaded successfully.");
        redirect_to('list_photos.php');
    } else
    {
        $message = join("<br/>", $photo->errors);
    }
}
?>


<?php include_layout_template('admin_header.php'); ?>

<h2>Photo Upload</h2>

<?php echo output_message($message); ?>
<form action="photo_upload.php" enctype="multipart/form-data" method="post">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>"/>
    <p><input type="file" name="file_upload"></p>
    <p>Caption: <input type="text" name="caption" value="" ></p>
    <input type="submit" name="submit" value="Upload">
</form>

<hr>

<?php
$photos = Photograph::find_all();
$photo = array_pop($photos);
if (isset($photo))
{
    $photo2 = $photo->filename;
    $photo3 = ucfirst($photo2);
    echo "Last uploaded photograph is : {$photo3}";
} else
{
    echo "There are no uploaded photos at this time!";
}
?>
<?php include_layout_template('admin_footer.php'); ?>

