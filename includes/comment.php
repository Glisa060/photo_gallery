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
        if (empty($photo_id) == FALSE && empty($author && !empty($body) == FALSE))
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
        if (empty($photo_id) == FALSE) {
            global $database;
            $sql = "SELECT * FROM " . self::$table_name . " WHERE photograph_id=" . $database->escape_value($photo_id) . " ORDER BY created ASC";
            return self::find_by_sql($sql);
        }
        return 0;
    }

    public function send_notification()
    {
      $mail = new PHPMailer;

      $mail->IsSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.mail.yahoo.com';                 // Specify main and backup server
      $mail->Port = 465;                                    // Set the SMTP port
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'milanglisic@ymail.com';                // SMTP username
      $mail->Password = 'password';                  // SMTP password
      $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted

      $mail->From = 'milanglisic@ymail.com';
      $mail->FromName = 'Milan';
      $mail->AddAddress('thegreatmilanglisic@gmail.com', 'Josh Adams');  // Add a recipient
      $mail->AddAddress('ellen@example.com');               // Name is optional

      $mail->IsHTML(true);                                  // Set email format to HTML
      $created = datetime_to_text($this->created);
      $mail->Subject = "New Comment from {$this->author}";
      $mail->Body    = <<<EMAILBODY
A new comment has been recieved in Photo Gallery.

At {$created}, {$this->author} wrote:

{$this->body}

EMAILBODY;

      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      if (!$mail->Send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        exit;
      }

      echo 'Message has been sent';
    }
}