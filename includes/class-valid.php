<?php

// static class
class FormValidation
{
    /**
     * make sure email is unique
     */
    public static function checkEmailUniqueness($email)
    {
        //check if already used by another user
        $user = DB::connect()->select(
            'SELECT * FROM users where email = :email',
            [
                'email' => $email
            ]
        );

        // if user with same email exists
        
        if($user) {
            return 'Email is already in-use';
        }

        return false;
    }



    /**
     * do all the form validation
     */
    public static function validate( $data, $rules = [] )
    {
        $error = false;

        // do all the form validation
        foreach( $rules as $key => $condition ) 
        {
            switch( $condition ) 
            {
                // make sure the value is not empty
                case 'required':
                    if ( empty( $data[$key] ) )
                    {
                        $error .=  'This field (' . $key . ') is empty<br />';
                    }
                    break;

                //make sure password is longer than 8
                case 'password_check':
                    // step 1 make sure password field is filled
                    if( empty( $data[$key] ) )
                    {
                        $error .=  'This field (' . $key . ') is empty<br />';
                    }
                    // step 2 make sure length is more than 8 chars
                    else if(strlen( $data[$key] ) < 8){
                        $error = 'Password must contain 8 or more characters<br />';
                    }
                    break;
                // make sure password is match
                case 'is_password_match':
                    if ( $data['password'] !== $data['confirm_password'] ) {
                        $error .= 'Password do not match<br />';
                    }
                    break;
                // make sure the email is valid
                case 'email_check':
                    if ( !filter_var( $data[$key], FILTER_VALIDATE_EMAIL ) ) 
                    {
                        $error .= "Email is invalid<br />";
                    }
                    break;
                case 'login_form_csrf_token':
                    if ( !CSRF::verifyToken($data[$key], 'login_form') ) 
                    {
                        $error .= "Invalid token<br />";
                    }
                    break;
                case 'signup_form_csrf_token':
                    if ( !CSRF::verifyToken($data[$key], 'signup_form') ) 
                    {
                        $error .= "Invalid token<br />";
                    }
                    break;
                case 'edit_user_form_csrf_token':
                    if ( !CSRF::verifyToken($data[$key], 'edit_user_form') ) 
                    {
                        $error .= "Invalid token<br />";
                    }
                    break;
                case 'add_user_form_csrf_token':
                    if ( !CSRF::verifyToken($data[$key], 'add_user_form') ) 
                    {
                        $error .= "Invalid token<br />";
                    }
                    break;
                case 'delete_user_form_csrf_token':
                    if ( !CSRF::verifyToken($data[$key], 'delete_user_form') ) 
                    {
                        $error .= "Invalid token<br />";
                    }
                    break;
                case 'edit_post_form_csrf_token':
                    if ( !CSRF::verifyToken($data[$key], 'edit_post_form') ) 
                    {
                        $error .= "Invalid token<br />";
                    }
                    break;
                case 'add_post_form_csrf_token':
                    if ( !CSRF::verifyToken($data[$key], 'add_post_form') ) 
                    {
                        $error .= "Invalid token<br />";
                    }
                    break;
                case 'delete_post_form_csrf_token':
                    if ( !CSRF::verifyToken($data[$key], 'delete_post_form') ) 
                    {
                        $error .= "Invalid token<br />";
                    }
                    break;
            }
        } // end - foreach

        return $error;
    }
}