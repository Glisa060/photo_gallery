<?php
require_once 'initialize.php';
require_once LIB_PATH.DS.'database.php';

class Comment extends DatabaseObject
{
    protected static $table_name = 'comments';
    protected static $db_fields = array('id','photograph_id', 'created', 'author', 'body');
    public $id;
    public $photograph_id;
    public $created;
    public $author;
    public $body;

    public static function make($photo_id, $author="Anonymous", $body='')
    {
        if (!empty($photo_id) && !empty($author && !empty($body)))
        {
            $comment = new Comment();
            $comment->photograph_id = (int)$photo_id;
            $comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
            $comment->author = $author;
            $comment->body = $body;
            return $comment;
        } else { return false; }
    }

    public static function find_comments_on($photo_id=0)
    {
        if (!empty($photo_id)) {
            global $database;
            $sql = "SELECT * FROM " . self::$table_name . " WHERE photograph_id=" . $database->escape_value($photo_id) . " ORDER BY created ASC";
            return self::find_by_sql($sql);
        }
        return 0;
    }

    public static function send_notification($author = '', $body = '')
    {
      $mail = new PHPMailer;

      $mail->IsSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.mail.yahoo.com';                 // Specify main and backup server
      $mail->Port = 465;                                    // Set the SMTP port
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'milanglisic@ymail.com';                // SMTP username
      $mail->Password = '88a97A2222';                  // SMTP password
      $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted

      $mail->From = 'milanglisic@ymail.com';
      $mail->FromName = 'Milan';
      $mail->AddAddress('thegreatmilanglisic@gmail.com', 'Josh Adams');  // Add a recipient
      $mail->AddAddress('ellen@example.com');               // Name is optional

      $mail->IsHTML(true);                                  // Set email format to HTML

      $mail->Subject = "New Comment from {$author}";
      $mail->Body    = "Comment: {$body}";
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      if(!$mail->Send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        exit;
      }

      echo 'Message has been sent';
    }
}