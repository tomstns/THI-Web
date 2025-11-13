<?php
namespace Model;

use JsonSerializable;

class Friend implements JsonSerializable {
    private $username;
    private $status; 

    public function __construct($username = null) {
        $this->username = $username;
        $this->status = 'requested';
    }

    public function getUsername() {
        return $this->username;
    }

    public function getStatus() {
        return $this->status;
    }
    
    public function accept() {
        $this->status = 'accepted';
    }

    public function dismiss() {
        $this->status = 'dismissed';
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public static function fromJson($data): Friend {
        $friend = new Friend();
        foreach ($data as $key => $value) {
            if (property_exists($friend, $key)) {
                $friend->{$key} = $value;
            }
        }
        return $friend;
    }
}
?>