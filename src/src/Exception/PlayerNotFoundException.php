<?php
namespace App\Exception;

class PlayerNotFoundException extends \Exception
{
    public function __construct($id)
    {
        return parent::__construct("Player with id $id not found.");
    }
}