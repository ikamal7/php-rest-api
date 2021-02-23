<?php
class Messages {
    // Connection
    private $conn;

    // Table
    private $db_table = "bkash_messages";

    // Columns
    public $id;
    public $message;
    public $created;

    // Db connection
    public function __construct( $db ) {
        $this->conn = $db;
    }

    // GET ALL
    public function getMessages() {
        $sqlQuery = "SELECT * FROM {$this->db_table}";
        $stmt     = $this->conn->prepare( $sqlQuery );
        $stmt->execute();

        return $stmt;
    }

    // CREATE
    public function createMessage() {
        $sqlQuery = "INSERT INTO  { $this->db_table } SET
                        message = :message,
                        created = :created";

        $stmt = $this->conn->prepare( $sqlQuery );

        // sanitize
        $this->message = htmlspecialchars( strip_tags( $this->message ) );
        $this->created = htmlspecialchars( strip_tags( $this->created ) );

        // bind data
        $stmt->bindParam( ":message", $this->message );
        $stmt->bindParam( ":created", $this->created );

        if ( $stmt->execute() ) {
            return true;
        }

        return false;
    }

    // READ single
    public function getSingleMessage() {
        $sqlQuery = "SELECT * FROM {$this->db_table} WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare( $sqlQuery );

        $stmt->bindParam( 1, $this->id );

        $stmt->execute();

        $dataRow = $stmt->fetch( PDO::FETCH_ASSOC );

        $this->message = $dataRow['message'];
        $this->created = $dataRow['created'];
    }

    // UPDATE
    public function updateMessage() {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        message = :message,
                        created = :created
                    WHERE
                        id = :id";

        $stmt = $this->conn->prepare( $sqlQuery );

        $this->message = htmlspecialchars( strip_tags( $this->message ) );
        $this->created = htmlspecialchars( strip_tags( $this->created ) );
        $this->id      = htmlspecialchars( strip_tags( $this->id ) );

        // bind data
        $stmt->bindParam( ":message", $this->message );
        $stmt->bindParam( ":created", $this->created );
        $stmt->bindParam( ":id", $this->id );

        if ( $stmt->execute() ) {
            return true;
        }

        return false;
    }

    // DELETE
    function deleteMessage() {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt     = $this->conn->prepare( $sqlQuery );

        $this->id = htmlspecialchars( strip_tags( $this->id ) );

        $stmt->bindParam( 1, $this->id );

        if ( $stmt->execute() ) {
            return true;
        }

        return false;
    }

}
?>