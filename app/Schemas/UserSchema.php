<?php

namespace App\Schemas;

class UserSchema
{
    /**
     * Simple data object representing input data for creating/updating a user.
     */
    public string $name;
    public string $email;
    public ?string $password;

    public function __construct(string $name, string $email, ?string $password = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
}


