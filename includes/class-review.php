<?php

class Posts{

    public static function getAllPosts($user_id)
    {
        if(Authentication::isUser()) {
            return DB::connect()->select(
                'SELECT * FROM posts WHERE user_id = :id ORDER BY id DESC',
                [
                    'user_id' => $user_id
                ],
                true
            );
        }

        return DB::connect()->select(
            'SELECT * FROM posts ORDER BY id DESC',
            [],
            true
        );
    }

    public static function postById($post_id)
    {
        return DB::connect()->select(
            'SELECT * FROM posts WHERE id = :id',
            [
                'id' => $post_id
            ]
        );
    }

    // get all published posts
    public static function getPublishPosts()
    {
        return DB::connect()->select(
            'SELECT * FROM posts WHERE status = :status ORDER BY id DESC',
            [
                'status' => 'publish'
            ],
            true
        );
    }

    public static function createPost($title, $content, $user_id)
    {
        return DB::connect()->insert(
            'INSERT INTO posts (title, content, user_id)
            VALUES (:title, :content, :user_id)',
            [
                'title' => $title,
                'content' => $content,
                'user_id' => $user_id
            ]
        );
    }

    public static function deletePost($post_id)
    {
        return DB::connect()->delete(
            'DELETE FROM posts where id = :id',
            [
                'id' => $post_id
            ]
        );
    }

    public static function updatePost($id, $title,$content, $status )
    {
        return DB::connect()->update(
            'UPDATE posts SET 
            title = :title, content = :content, status = :status 
            WHERE id = :id',
            [
                'id' => $id,
                'title' => $title,
                'content' => $content,
                'status' => $status
            ]
        );
    }
}