<?php
require_once 'templates/header.php';
?>

<?php
$host     = 'localhost'; // Because MySQL is running on the same computer as the web server
$database = 'demo'; // Name of the database you use (you need first to CREATE DATABASE in MySQL)
$user     = 'root'; // Default username to connect to MySQL is root
$password = 'mysql'; // Default password to connect to MySQL is empty

// TO DO: CREATE CONNECTION TO DATABASE
// Read file: https://www.w3schools.com/php/php_mysql_connect.asp
try {
    $db = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
    $sql = "CREATE TABLE posts (
        id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        name varchar(100) NOT NULL,
        message varchar(200) NOT NULL
      )";
      $db->exec($sql);
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['username']) && !empty($_POST['message'])) :
    try {
        $username = $_POST['username'];
        $message = $_POST['message'];
//     $db->query( // TO DO: INSERT NEW POST IN DATABASE
        $sqlInsert = "INSERT INTO posts (name, message) VALUES (:name, :message)";
        $stmt = $db ->prepare($sqlInsert);
        $stmt -> bindParam(':name',$username);
        $stmt -> bindParam(':message',$message);
        $stmt->execute();
        echo "isert data succesfully" ;
    } catch (PDOException $e) {
        echo "unsuccesful conect: ". $e -> getMessage();

        //throw $th;
    }
endif;
?>

<?php
// TO DO: SELECT ALL POSTS FROM DATABASE
    $selectQuery ="SELECT * FROM posts";
    $result = $db -> query($selectQuery);


    $posts = $result -> fetchAll(PDO::FETCH_ASSOC);


foreach ($posts as $post) :
?>
    <div class="card">
        <div class="card-header">
            <span><?php echo $post['name']; ?></span>
        </div>
        <div class="card-body">
            <p class="card-text"><?php echo $post['message']; ?></p>
        </div>
    </div>
    <hr>
<?php
endforeach;
?>

<form action="#" method="post">
    <div class="row mb-3 mt-3">
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter Name" name="username">
        </div>
    </div>

    <div class="mb-3">
        <textarea name="message" placeholder="Enter message" class="form-control"></textarea>
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Add new post</button>
    </div>
</form>

<?php
require_once 'templates/footer.php';
?>