<?php require_once '../../includes/initialize.php';
    if(!$session->is_logged_in())
    {
    redirect_to("login.php");
    }
?>


<?php

$file = SITE_ROOT.DS.'logs'.DS.'log.txt';
if (filesize($file) > 0)
{
    if ($handle = fopen($file, 'r'))
    {
        $content = fread($handle, filesize($file));
        fclose($handle);
    }
    else
    {
        echo "Error file can't open for reading!";
    }
}

if (isset($_GET['clear']))
{
    if ($_GET['clear'] == 'true')
    {
        if (file_exists($file = SITE_ROOT . DS . 'logs' . DS . 'log.txt')) {
            $handle = fopen($file, 'w');
            fclose($handle);

        }
        else
        {
            echo "Error file doesn't exist!";
        }
    }
    else
    {
        echo "Data not found.";
    }
}
?>


<?php include_layout_template('admin_header.php'); ?>

<h2>Log File</h2>

<?php if (isset($content))
{
    echo nl2br($content);
}

else
{
    echo "There is no content to be deleted";
}
?>

<?php include_layout_template('admin_footer.php'); ?>