<?php
namespace Model;

use JsonSerializable;

class User implements JsonSerializable {
    
    // Eigenschaften, die vom Backend geladen werden
    private $username;
    private $firstName;
    private $lastName;
    private $corT; // Coffee or Tea
    private $aboutMe;
    private $chatLayout; // FEHLENDE EIGENSCHAFT

    public function __construct($username = null) {
        $this->username = $username;
    }

    // --- Getter-Methoden (Hattest du schon) ---

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

    public function getChatLayout() {
        return $this->chatLayout;
    }

    // --- Setter-Methoden (NEU) ---
    
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setCorT($corT) {
        $this->corT = $corT;
    }

    public function setAboutMe($aboutMe) {
        $this->aboutMe = $aboutMe;
    }

    public function setChatLayout($chatLayout) {
        $this->chatLayout = $chatLayout;
    }


    // --- Serialisierung (Unverändert) ---

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