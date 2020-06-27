<?php

namespace App\User;

use App\App;

class BaseUser
{
    protected $id;
    protected $email;
    protected $vorname;
    protected $nachname;
    private $accessLevel;

    public function __construct(int $id, string $email, string $vorname, string $nachname, $accessLevel)
    {
        $this->id = $id;
        $this->email = $email;
        $this->vorname = $vorname;
        $this->nachname = $nachname;
        $this->accessLevel = $accessLevel;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getVorname(): string
    {
        return $this->vorname;
    }

    public function setVorname(string $vorname)
    {
        $this->vorname = $vorname;
    }

    public function getNachname(): string
    {
        return $this->nachname;
    }

    public function setNachname(string $nachname)
    {
        $this->nachname = $nachname;
    }

    public function getAccessLevel(): int
    {
        return $this->accessLevel;
    }

    public function setAccessLevel(int $accessLevel)
    {
        $this->accessLevel = $accessLevel;
    }

    public function __toString()
    {
        return $this->vorname  . ' ' . $this->nachname;
    }
}