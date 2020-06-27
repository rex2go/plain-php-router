<?php

namespace Psr\Container;

interface ContainerInterface {

    function get(string $id);

    function has(string $id);
}