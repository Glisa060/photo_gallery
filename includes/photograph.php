<?php
require_once 'initialize.php';
require_once LIB_PATH.DS.'database.php';

class Photograph extends DatabaseObject
{
    protected static $table_name = 'photographs';
    protected static $db_fields = array('id','filename', 'type', 'size', 'caption');
    public $id;
    public $filename;
    public $type;
    public $size;
    public $caption;

    private $temp_path;
    protected $upload_dir='images';
    public $errors = array();
    protected $upload_errors = array(
        UPLOAD_ERR_OK => "No Errors.",
        UPLOAD_ERR_INI_SIZE => "Larger than upload_max_filesize.",
        UPLOAD_ERR_FORM_SIZE => "Larger than form MAX_FILE_SIZE.",
        UPLOAD_ERR_PARTIAL => "Partial upload.",
        UPLOAD_ERR_NO_FILE => "No file.",
        UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
        UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
        UPLOAD_ERR_EXTENSION => "File upload stopped by extension."
    );

    public function attach_file($file)
    {
        if (!$file || empty($file) || !is_array($file))
        {
            $this->errors[] = "No file was uploaded!";
            return false;
        } elseif($file['error'] != 0)
        {
            $this->errors[] = $this->upload_errors[$file['error']];
            return false;
        }
        $this->temp_path = $file['tmp_name'];
        $this->filename = basename($file['name']);
        $this->type = $file['type'];
        $this->size = $file['size'];
        return true;
    }

    public function save()
    {
        if (isset($this->id))
        {
            $this->update();
        } else
        {
            if (!empty($this->errors)) {return false;}
            if (strlen($this->caption) > 255)
            {
                $this->errors[] = "The caption can only be 255 characters long.";
                return false;
            }

            $target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;

            if (file_exists($target_path))
            {
                $this->errors[] = "The file {$this->filename} already exists!";
                return false;
            }

            if(move_uploaded_file($this->temp_path, $target_path))
            {
                if($this->create())
                {
                    unset($this->temp_path);
                    return true;
                }
            } else
            {
                $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
                return false;
            }
        }
        return 0;
    }

    public function destroy()
    {
        if ($this->delete())
        {
            $target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
            return unlink($target_path) ? true : false;
        } else
        {
            return false;
        }
    }

    public function image_path()
    {
        return $this->upload_dir.DS.$this->filename;
    }

    public function size_as_text()
    {
        if ($this->size < 1024)
        {
            return "{$this->size} bytes";
        } elseif ($this->size < 1048576)
        {
            $size_kb = round($this->size/1048576, 1);
            return "{$size_kb} MB";
        }
        return 0;
    }

    public function comments()
    {
        return Comment::find_comments_on($this->id);
    }
}