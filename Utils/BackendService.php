<?php
namespace Utils;

use Model\Friend;
use Model\User;


function join_paths() {
    $paths = array();
    foreach (func_get_args() as $arg) {
        if ($arg !== '') { $paths[] = $arg; }
    }
    return preg_replace('#/+#','/',join('/', $paths));
}

class BackendService{
    public $link = "";
    private $base = "";
    private $id = "";

    public function __construct($base, $id) {
        $this->link = join_paths($base, $id);
        $this->base = $base;
        $this->id = $id;
    }


    public function login($username, $password){
        try{
            $result = HttpClient::post(join_paths($this->link, "login"), array(
                "username" => $username,
                "password" => $password
            ));
            $_SESSION["chat_token"] = $result->token;
            return true;
        }catch (\Exception $e){
            error_log($e);
        }
        return false;
    }

    public function register($username, $password){
        try{
            $result = HttpClient::post(join_paths($this->link, "register"), array(
                "username" => $username,
                "password" => $password
            ));
            $_SESSION["chat_token"] = $result->token;
            return true;
        } catch (\Exception $e){
            error_log($e);
        }
        return false;
    }
    

    public function loadUser($username){
        try {
            $user = HttpClient::get(join_paths($this->link, "user", $username), $_SESSION["chat_token"]);
            return User::fromJson($user);
        } catch (\Exception $e) {
            error_log($e);
        }
        return false;
    }

    public function saveUser($user){
        try {
            HttpClient::put(join_paths($this->link, "user", $user->getUsername()), $user, $_SESSION["chat_token"]);
            return true;
        } catch (\Exception $e) {
            error_log($e);
        }
        return false;
    }
 
    public function loadMessages($chatpartner){
        try{
            $messages = HttpClient::get(join_paths($this->link, "message", $chatpartner), 
                $_SESSION["chat_token"]);
            return $messages;
        } catch(\Exception $e){
            error_log($e);
        }
        return false;
    }

    public function loadFriends(){
        try{
            $friend = HttpClient::get(join_paths($this->link, "friend"), $_SESSION["chat_token"]);
            $friends = array();
            foreach($friend as $element){
                $friends[] = Friend::fromJson($element);
            }
            return $friends;
        } catch(\Exception $e){
            error_log($e);
        }
        return false;
    }


    public function loadUsers() {
        try{
            $users = HttpClient::get(join_paths($this->link, "user"), $_SESSION["chat_token"]);
            return $users;
        }catch (\Exception $e){
            error_log($e);
        }
        return false;
    }


    public function sendMessage($message) {
        try {
            $reply = HttpClient::post(join_paths($this->link, "message"),
                array("message" => $message->msg, "to" => $message->to),
                $_SESSION["chat_token"]);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }


    public function friendRequest($friend){
        try{
            HttpClient::post(join_paths($this->link, "friend"), $friend, $_SESSION["chat_token"]);
            return true;
        } catch (\Exception $e){
            error_log($e);
        }
        return false;
    }


    public function friendAccept($friend){
        try{
            HttpClient::put(join_paths($this->link, "friend", $friend), array("status" => "accepted"), $_SESSION["chat_token"]);
            return true;
        }catch (\Exception $e){
            error_log($e);
        }
        return false;
    }


    public function friendDismiss($friend){
        try{
            HttpClient::put(join_paths($this->link, "friend", $friend), array("status" => "dismissed"), $_SESSION["chat_token"]);
            return true;
        }catch (\Exception $e){
            error_log($e);
        }
        return false;
    }


    public function removeFriend($friend){
        try{
            HttpClient::delete(join_paths($this->link, "friend", $friend), $_SESSION["chat_token"]);
            return true;
        }catch (\Exception $e){
            error_log($e);
        }
        return false;
    }


    public function userExists($username){
        try {
            HttpClient::get(join_paths($this->link, "user", $username));
            return true;
        } catch (\Exception $e) {
            error_log($e);
        }
        return false;
    }

    

    public function getUnread(){
        try{
            $unread = HttpClient::get(join_paths($this->link, "unread"), $_SESSION["chat_token"]);
            return $unread;
        }catch (\Exception $e){
            error_log($e);
        }
        return false;
    }
}
?>