<?php
require_once 'initialize.php';

function strip_zeros_from_date($marked_string='')
{
    //first remove the marked zeros
    $no_zeros = str_replace('*0', '', $marked_string);
    // then remove any remaining marks
    $cleaned_string = str_replace('*','' , $no_zeros);
    return $cleaned_string;
}

function  redirect_to($location = NULL)
{
    if($location != NULL)
    {
        header("Location: $location");
        exit;
    }
}

function output_message($message='')
{
    if(!empty($message))
    {
        return "<p class=\"message\">{$message}</p>";
    }
    else
    {
        return '';
    }
}

function __autoload($class_name)
{
    $class_name = strtolower($class_name);
    $path = LIB_PATH.DS."{$class_name}.php";
    if(file_exists($path))
    {
        require_once($path);
    }
    else
    {
        die("The file {$class_name}.php could not be found!");
    }
}

function include_layout_template($template='')
{
    include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function log_action($action)
{
    $file = SITE_ROOT.DS.'logs'.DS.'log.txt';
    if($handle = fopen($file, 'a+'))
    {
        $first_part = strftime('%m/%d/%Y %H:%M', filemtime($file));
        $second_part = "Login: {$action} has logged in." . PHP_EOL;
        $part = $first_part. " | " . $second_part;
        fwrite($handle, $part);
        fclose($handle);
    }
    else
    {
        echo "Error file can't be written!";
    }
}

function datetime_to_text($datetime='')
{
    $unixdatetime = strtotime($datetime);
    return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}

function get_photos()
{

}
