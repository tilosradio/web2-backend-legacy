<?php
namespace Radio\Model;

class Author
{
    public $id;
    public $name;
    public $nick;
    public $iconUrl;

    public function exchangeArray($data) {
        $this->id           = (!empty($data['id'])) ? $data['id'] : null;
        $this->name         = (!empty($data['name'])) ? $data['name'] : null;
        $this->nick         = (!empty($data['nick'])) ? $data['nick'] : null;   
        $this->iconUrl      = (!empty($data['iconUrl'])) ? $data['iconUrl'] : null;
    }


}

?>