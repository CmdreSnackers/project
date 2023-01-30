<?php

class DB
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                'mysql:host=devkinsta_db;dbname=project', 
                'root',
                'WSC2rkMYbGqpj0v7'
            );
        } catch( PDOException $error ) {
            die("Database connection failed");
        }
    }

    public static function connect()
    {
        return new self(); 

    }

    public function select($sql, $data = [], $is_list = false)
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($data);

        if($is_list) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {

            return $statement->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function insert($sql, $data = [])
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($data);
        return $this->db->lastInsertId();
    }

    public function update($sql, $data = [])
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($data);
        return $statement->rowCount();
    }

    public function delete($sql, $data = [])
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($data);
        return $statement->rowCount();
    }

    public static function callAPI( $api_url = '', $method = 'GET', $formdata = [], $headers = [] ) {

        $curl = curl_init();
    

        $curl_props = [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_CUSTOMREQUEST => $method
        ];

        if ( !empty( $formdata ) ) {
            $curl_props[ CURLOPT_POSTFIELDS ] = json_encode( $formdata );
        }

        if ( !empty( $headers ) ) {
            $curl_props[ CURLOPT_HTTPHEADER ] = $headers;
        }

        curl_setopt_array( $curl, $curl_props );

        $response = curl_exec( $curl );

        $error = curl_error( $curl );

        curl_close( $curl );
    
        if ( $error )
            return 'API not working';
    
        return json_decode( $response );
    }
}