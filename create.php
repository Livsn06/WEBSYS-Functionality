
<?php 
require 'config.php';

# identify if session already statrted [else it direct to log in page]
session_start();
if(!$_SESSION["user"] && !$_SESSION["pass"]){
    session_abort();
    header('Location: login.php');

 }

 # get user username and password
if(isset($_GET['username'])){
    $user = $_GET['username'];
    $username = "";
    $pass = "";
    $query = "SELECT username, password FROM ACCOUNTS WHERE username='".$user."'";
    $result = $conn->query($query); 

    while($data = $result->fetch_assoc()){
        $username = $data['username'];
        $pass = $data['password'];
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body >
    
    <!-- ---- return home ---- -->
    <ul>
        <a href="home.php"><li>Back to home</li></a> 
    </ul>
    <!-- ----- -->

    <!-- ------------------ form for creating new blog content--------------------- -->
    <form method="post" enctype="multipart/form-data">
        <h2>uSERNAME: <?php if(isset($_GET['username'])){ echo $username; }?></h2>
        <h2>pROFILE: <?php if(isset($_GET['username'])){ echo $pass; }?></h2><br>

        <input type="text" name="entry" placeholder="title"><br>
        <textarea name="content" cols="30" rows="10"></textarea><br>
        <select name="category[]" >
            <option value="Leisure & Travel">Leisure & Travel</option>
            <option value="Sports & Game">Sports & Game</option>
            <option value="Fashions & Accessories">Fashions & Accessories</option>
        </select><br>
        <input type="file" accept="image/png, image/jpg, image/jpeg"  name="upload"><br><br>
        
        <button type="submit" name="post" value="post">Post Content</button>
        
    </form>
    <!-- ------- -->
</body>
</html>

<?php

    # upload and post the content created [this is directed to homepage]
    if(isset($_POST['post'])){
        
        $entry = $_POST['entry'];
        $content = $_POST['content'];
        $oldDIR = $_FILES['upload']['tmp_name'];
        $fileNAME = $_FILES['upload']['name'];
        $newDIR = "uploads/uploaded".$fileNAME;
        $cat = "";
        foreach($_POST['category'] as $c){
            $cat = $c;
        }

        move_uploaded_file($oldDIR , $newDIR);//<-- move to new folder directory
        
        # the file uploaded via new image path name [$newDIR]
        $query = "INSERT INTO POST (username, blog_title, blog_content, dateTime_created, blog_cat, blog_pic) ".
                 "VALUES ('$username','$entry','$content',LOCALTIMESTAMP(),'$cat','$newDIR')";

        mysqli_query($conn, $query);
        header('Location: home.php'); //<-- direct to homepage
    }




?>