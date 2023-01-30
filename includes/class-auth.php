<?php

class Authentication
{

    public static function login( $email, $password )
    {
        $user_id = false;

        $user = DB::connect()->select(
            'SELECT * FROM users where email = :email',
            [
                'email' => $email
            ]
        );

        if ( $user ) {

            if ( password_verify( $password, $user['password'] ) ) {
                $user_id = $user['id'];
            }
        }

        return $user_id;
    }

    public static function signup( $name, $email , $password )
    {

        return DB::connect()->insert(
            'INSERT INTO users (name,email,password)
            VALUES (:name, :email, :password)',
            [
                'name' => $name,
                'email' => $email,
                'password' => password_hash( $password, PASSWORD_DEFAULT ),
            ]
        );

    }

    public static function logout()
    {
        unset($_SESSION['user']);
    }


    public static function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }




    public static function setSession( $user_id )
    {

        $user = DB::connect()->select(
            'SELECT * from users where id = :id',
            [
                'id' => $user_id
            ]
        );

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    }

    public static function getRole()
    {
        if(self::isLoggedIn()) {
            return $_SESSION['user']['role'];
        }
        return false;
    }


    public static function isAdmin()
    {
        return self::getRole() == 'admin';
    }

    public static function isSeller()
    {
        return self::getRole() == 'seller';
    }

    public static function isUser()
    {
        return self::getRole() == 'user';
    }


    public static function whoCanAccess($role)
    {
        if(self::isLoggedIn()) {
            switch($role) {
                case 'admin':
                    return self::isAdmin();
                case 'seller':
                    return self::isSeller() || self::isAdmin();
                case 'user':
                    return self::isUser() || self::isSeller() || self::isAdmin();
                case 'useronly':
                    return self::isUser();
                case 'selleronly':
                    return self::isSeller();
            }
        }
        return false;
    }
}