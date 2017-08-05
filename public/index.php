<?php require_once("../includes/initialize.php"); ?>
<?php
      $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

      $per_page = 3;

      $total_count = Photograph::count_all();

      // Find all photos
	//$photos = Photograph::find_all();
      $pagination = new Pagination($page, $per_page, $total_count);

      $sql = "SELECT * FROM photographs ";
      $sql .= "LIMIT {$per_page} ";
      $sql .= "OFFSET {$pagination->offset()}";
      $photos = Photograph::find_by_sql($sql);
?>

<?php include_layout_template('header.php'); ?>

<?php foreach($photos as $photo): ?>
  <div style="float: left; margin-left: 20px;">
     <a href="photo.php?id=<?php echo $photo->id ?>">
      <img src="<?php echo $photo->image_path(); ?>" width="200" />
     </a>
      <p><?php echo $photo->caption; ?></p>
  </div>
<?php endforeach; ?>

<?php include_layout_template('footer.php'); ?>
