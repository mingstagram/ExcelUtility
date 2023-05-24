<?php
class ExcelUpload {
    public $conn ;
    function __construct (){
        require_once('config.php');
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
    }
    // function insert_info($eno, $team, $name, $position) {
    //     $eno = (int)$eno;
    //     $stmt = $this -> conn -> prepare("INSERT into test (eno, team, name, position,) values (?,?,?,?)");
    //     $stmt -> bind_param('isss', $eno, $team, $name, $position);
    //     $result = $stmt -> execute();
    //     $stmt -> close();
    //     return $result;
    // }
    function insert_info($data) { 
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $sql = "
            INSERT into topic 
            (title
            , description
            , created
            , author_id) 
            values 
            (
            '{$data['title']}'
            ,'{$data['description']}'
            ,'{$data['created']}'
            ,(SELECT id FROM author WHERE NAME = '{$data['author']}')
            )  
        ";  
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }
    function select_info($id) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $sql = "SELECT * from topic where id = {$id} order by id limit 1"; 

        $result = mysqli_query($conn, $sql); 
        $row = $result->fetch_assoc();
        $conn->close(); 
        return $row;
    }
    function update_info($data) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        $sql = "
            UPDATE topic 
            set title = '{$data['title']}' , 
            description = '{$data['description']}'  , 
            created = '{$data['created']}'  , 
            author_id = (SELECT id FROM author WHERE NAME = '{$data['author']}' )
            where id = '{$data['id']}' 
        "; 
        $stmt = $conn -> prepare($sql);
        echo $conn->error;
        $result = $stmt -> execute();
        $stmt -> close();
        return $result;
    }
    function get_total_employee() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $sql = "SELECT count(*) total from topic";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        $total = $row['total'];
        $conn->close(); 
        return $total;
    }
}
?>