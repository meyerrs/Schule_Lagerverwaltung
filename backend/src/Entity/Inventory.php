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

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'verantwortlicher_id', referencedColumnName: 'id', nullable: true)]
    private User $verantwortlicher;


    public function getID() : int
    {
        return $this->id;
    }

    public function getName() :string
    {
        return $this->name;
    }

    public function setName(string $name): string
    {
        return $this->name = $name;
    }

    public function getAbteilung() : string
    {
        return $this->abteilung;
    }

    public function setAbteilung(string $abteilung) : string
    {
        return $this->abteilung = $abteilung;
    }

    public function getGruppe() : string
    {
        return $this->gruppe;
    }

    public function setGruppe(string $gruppe) : string
    {
        return $this->gruppe = $gruppe;
    }

    public function getFach() : string
    {
        return $this->fach;
    }

    public function setFach(string $fach) :string
    {
        return $this->fach = $fach;
    }

    public function getOrt() : string
    {
        return $this->ort;
    }

    public function setOrt(string $ort) :string
    {
        return $this->ort = $ort;
    }

     public function getVerantwortlicher(): User
    {
        return $this->verantwortlicher;
    }

    public function setVerantwortlicher(User $verantwortlicher): User
    {
       return $this->verantwortlicher = $verantwortlicher;
    }
}