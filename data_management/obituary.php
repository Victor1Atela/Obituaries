<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Obituary</title>
    <style>
        body {
            font-family: Georgia, Times, 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background-color: chartreuse;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid whitesmoke;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.8); 
        }
        .form-container input, .form-container textarea, .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.9); 
        }
        .form-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: goldenrod;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .bg-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .social-links {
            margin-top: 20px;
            text-align: center;
        }
        .social-links a {
            margin: 0 10px;
            color: blue;
            text-decoration: none;
        }
        .social-links a:hover {
            color: red;
        }
    </style>
</head>
<body>
    
    <video class="bg-video" autoplay muted loop>
        <source src="review.mp4" type="video.mp4">
        Your browser does not support the video tag.
    </video>

   
    <div class="form-container">
        <h1>Add Obituary</h1>
        <form action="obituary.php" method="post" onsubmit="return validateForm()">
            <label>Name:</label><br>
            <input type="text" name="name" required><br>
            
            <label>Date of Birth:</label><br>
            <input type="date" name="D_O_B" required><br>
            
            <label>Date of Death:</label><br>
            <input type="date" name="D_O_D" required><br>

            <label>Content:</label><br>
            <textarea name="content" rows="5" required></textarea><br>

            <label>Author:</label><br>
            <input type="text" name="author" required><br>
            
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>

   
    <script>
        function validateForm() {
            const author = document.getElementsByName("author")[0].value;
            if (author.trim() === "") {
                alert("Please enter the author's name.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>


<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'obituary_platform');

$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if(!$db){
    die("Connection failed: " . mysqli_connect_error());
}
?>
<?php
class Obituary {
    public static function getAll() {
        global $db;
        $query = "SELECT * FROM obituaries ORDER BY created_at DESC";
        $result = mysqli_query($db, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function getById($id) {
        global $db;
        $query = "SELECT * FROM obituaries WHERE id = $id";
        $result = mysqli_query($db, $query);
        return mysqli_fetch_assoc($result);
    }

    public static function create($name, $D_O_B, $D_O_d, $content,$author) {
        global $db;
        $query = "INSERT INTO obituaries (name, D_O_B, D_O_D, content,author)
                  VALUES ('$name', '$D_O_B', '$D_O_B', '$content', '$author')";
        return mysqli_query($db, $query);
    }

    public static function update($id, $name, $D_O_B, $D_O_D, $content,$author) {
        global $db;
        $query = "UPDATE obituaries SET name='$name', date_of_birth='$D_O_B',
                  date_of_death='$D_O_B', content='$content' WHERE id = $id";
        return mysqli_query($db, $query);
    }

    public static function delete($id) {
        global $db;
        $query = "DELETE FROM obituaries WHERE id = $id";
        return mysqli_query($db, $query);
    }
}
?>


<?php
class ObituariesController {
    public function index() {
        $obituaries = Obituary::getAll();
        require('../views/obituaries/index.php');
    }

    public function show($id) {
        $obituary = Obituary::getById($id);
        require('../views/obituaries/show.php');
    }

    public function create() {
        require('../views/obituaries/create.php');
    }

    public function store($data) {
        Obituary::create($data['name'], $data['D_O_B'], $data['D_O_D'], $data['content'], $data['author']);
        header('Location: /');
        exit();
    }

    public function edit($id) {
        $obituary = Obituary::getById($id);
        require('../views/obituaries/edit.php');
    }

    public function update($id, $data) {
        Obituary::update($data['name'], $data['D_O_B'], $data['D_O_D'], $data['content'], $data['author']);
        header("Location: /show.php?id=$id");
        exit();
    }

    public function delete($id) {
        Obituary::delete($id);
        header('Location: /');
        exit();
    }
}
?>
