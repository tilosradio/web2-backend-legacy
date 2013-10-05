<?php
namespace Radio\Model;

class Show
{
    public $id;
    public $name;
    public $slug;
    public $description;
    public $bannerUrl;
    public $authors;
 

    public function exchangeArray($data) {
        $this->id           = (!empty($data['id'])) ? $data['id'] : null;
        $this->name         = (!empty($data['name'])) ? $data['name'] : null;
        $this->description  = (!empty($data['description'])) ? $data['description'] : null;   
    }


}

?>