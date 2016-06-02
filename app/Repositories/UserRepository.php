<?php

namespace App\Repositories;

use App\User;

class UserRepository {
    /**
     * Get User
     * @param string $email - email
     * @return type
     */
    public function getUser($email) {
        return User::queryUser($email);
    }

}
