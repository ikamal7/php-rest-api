<?php
header( "Access-Control-Allow-Origin: *" );
header( "Content-Type: application/json; charset=UTF-8" );

include_once '../config/database.php';
include_once '../class/messages.php';

$database = new Database();
$db       = $database->getConnection();

$items = new Messages( $db );

$stmt      = $items->getMessages();
$itemCount = $stmt->rowCount();


// echo json_encode( $itemCount );

if ( $itemCount > 0 ) {
    $messageArr              = array();
    $messageArr["body"]      = array();
    // $messageArr["itemCount"] = $itemCount;

    while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
        extract( $row );
        $e = array(
            "id"          => $id,
            "message"     => $message,
            "created"     => $created,
        );

        array_push( $messageArr["body"], $e );
    }
    echo json_encode( $messageArr );
} else {
    http_response_code( 404 );
    echo json_encode(
        array( "message" => "No record found." )
    );
}
?>