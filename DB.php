<?php
namespace Photos;

use mysqli;

class DB
{
    static $host = "localhost";
    static $user = "root";
    static $password = "";
    static $database = "photos";

    public $link;

    public function __construct()
    {
        $this->link = new mysqli(DB::$host, DB::$user, DB::$password, DB::$database);
        $this->link->set_charset("utf8");
    }

    public function get_all_photos()
    {
        $sql_result = $this->link->query("SELECT * FROM `photos` ORDER BY `id` DESC");
        return $sql_result->num_rows ? $sql_result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_user_photos($uid)
    {
        $uid = intval($uid);
        $sql_result = $this->link->query("SELECT * FROM `photos` WHERE `Uid`=$uid ORDER BY `id` DESC");
        return $sql_result->num_rows ? $sql_result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function check_user($login, $password)
    {
        $sql_result = $this->link->query("SELECT * FROM `users` WHERE `Email`='$login' AND `Password`='$password'");
        if ($sql_result->num_rows) {
            $user = $sql_result->fetch_assoc();
            return $user["id"];
        }
        return false;
    }

    public function check_login($login)
    {
        $sql_result = $this->link->query("SELECT * FROM `users` WHERE `Email`='$login'");
        return $sql_result->num_rows ? true : false;
    }

    public function new_user($login, $password)
    {
        $this->link->query("INSERT INTO `users` (Name, Password, Email) VALUES ('', '$password', '$login')");
    }

    public function new_photo($uid, $image, $text)
    {
        $uid = intval($uid);
        $this->link->query("INSERT INTO `photos` (Uid, Image, Text, Tags) VALUES ($uid, '$image', '$text', '')");
    }

    public function get_photo_by_id($photo_id)
    {
        $photo_id = intval($photo_id);

        $sql = "SELECT p.*, u.`Name`
                FROM `photos` p
                LEFT JOIN `users` u ON u.`Id` = p.`Uid`
                WHERE p.`Id` = $photo_id";

        $sql_result = $this->link->query($sql);

        if ($sql_result && $sql_result->num_rows) {
            return $sql_result->fetch_assoc();
        }

        return false;
    }

    public function get_photo_comments($photo_id)
    {
        $photo_id = intval($photo_id);

        $sql = "SELECT c.text, c.Post_date, u.Name 
                FROM comments c
                LEFT JOIN users u ON u.Id = c.uid
                WHERE c.pid = $photo_id
                ORDER BY c.Post_date DESC";

        $result = $this->link->query($sql);

        $comments = [];

        if ($result && $result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
        }

        return $comments;

    }

    public function add_comment($pid, $uid, $text)
    {
        $date = date("Y-m-d");

        $this->link->query("INSERT INTO `comments` (Pid, Uid, Text, Post_date) 
                        VALUES ($pid, $uid, '$text', '$date')");

        $last_id = $this->link->insert_id;

        $inserted_comment = $this->link->query("
        SELECT c.*, u.Name 
        FROM `comments` c
        LEFT JOIN `users` u ON u.Id = c.Uid
        WHERE c.Id = $last_id
    ");

        return $inserted_comment->fetch_assoc();
    }
}
