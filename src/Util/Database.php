<?php

namespace App\Util;

use mysqli;

class Database {
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = mysqli_connect("mysql:3306", "root", "123456", "block1");
    }

    public function getConnection(): mysqli {
        return $this->mysqli;
    }
}