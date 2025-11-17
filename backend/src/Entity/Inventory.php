<?php

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name : 'Inventory')]
class Inventory
{
    #[ORM\Column(type: Types:: INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(type: Types:: STRING)]
    private string $name;

    #[ORM\Column(type: Types:: STRING)]
    private string $abteilung;  

    #[ORM\Column(type: Types:: STRING)]
    private string $gruppe;

    #[ORM\Column(type: Types:: STRING)]
    private string $fach;

    #[ORM\Column(type: Types:: STRING)]
    private string $ort;


    public function getID(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName(string $name){
        return $this->name = $name;
    }

    public function getAbteilung(){
        return $this->abteilung;
    }

    public function setAbteilung(string $abteilung){
        return $this->abteilung = $abteilung;
    }

    public function getGruppe(){
        return $this->gruppe;
    }

    public function setGruppe(string $gruppe){
        return $this->gruppe = $gruppe;
    }

    public function getFach(){
        return $this->fach;
    }

    public function setFach(string $fach){
        return $this->fach = $fach;
    }

    public function getOrt(){
        return $this->ort;
    }

    public function setOrt(string $ort){
        return $this->ort = $ort;
    }
}