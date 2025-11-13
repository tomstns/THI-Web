<?php
namespace Model;

use JsonSerializable;

class User implements JsonSerializable {
    
    private $username;
    private $firstName;
    private $lastName;
    private $corT;
    private $aboutMe;

    public function __construct($username = null) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }
    
    public function getFirstName() {
        return $this->firstName;
    }
    
    public function getLastName() {
        return $this->lastName;
    }
    
    public function getCorT() {
        return $this->corT;
    }

    public function getAboutMe() {
        return $this->aboutMe;
    }


    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public static function fromJson($data): User {
        $user = new User();
        
        foreach ($data as $key => $value) {
            
            if (property_exists($user, $key)) {
                $user->{$key} = $value;
            }
        }
        return $user;
    }
}
?>