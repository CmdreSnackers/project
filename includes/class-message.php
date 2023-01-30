<?php

class Message
{
    public static function getAllMessages($user_id)
    {
        if(Authentication::isUser()) {
            return DB::connect()->select(
                'SELECT * FROM messages WHERE user_id = :id ORDER BY id DESC',
                [
                    'user_id' => $user_id
                ],
                true
            );
        }

        return DB::connect()->select(
            'SELECT * FROM messages ORDER BY id DESC',
            [],
            true
        );
    }

    public static function messageById($message_id)
    {
        return DB::connect()->select(
            'SELECT * FROM messages WHERE id = :id',
            [
                'id' => $message_id
            ]
        );
    }

    public static function createUserMessage($fromUser = [], $user_id)
    {
        return DB::connect()->insert(
            'INSERT INTO messages (fromUser, user_id)
            VALUES (:fromUser, :user_id)',
            [
                'fromUser' => $fromUser,
                'user_id' => $user_id
            ]
        );
    }

    public static function createSellerMessage($fromSeller = [], $user_id)
    {
        return DB::connect()->insert(
            'INSERT INTO messages (fromSeller, user_id)
            VALUES (:fromSeller, :user_id)',
            [
                'fromSeller' => $fromSeller,
                'user_id' => $user_id
            ]
        );
    }


}