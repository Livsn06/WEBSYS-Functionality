<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commets</title>
</head>
<body>

    <!-- ---- return home ---- -->
    <ul>
        <a href="home.php"><li>Back to home</li></a> 
    </ul>
    <!-- ----- -->



    <!-- -------------- GETTING THE POSTED BLOG SELECTED -------------- -->
    <?php

    require 'config.php';

    # identify if session already statrted [else it direct to log in page]
    session_start();
    if(!$_SESSION["user"] && !$_SESSION["pass"]){
        session_abort();
        header('Location: login.php');
     }
    $blogid = $_GET['idno']; //<--- declare variable to get posted blog ID ['used for inserting comment to database']

    #select the blog selected from database
    $query = "SELECT username,  blogid, blog_title, blog_content, DATE_FORMAT(datetime_created, \"%M %d, %Y at %r\") as date, blog_cat, blog_pic FROM POST WHERE blogid=".$_GET['idno']."";

    $result = $conn->query($query);

    
    while($data = $result->fetch_assoc()){  
    ?>

     <!-- username who commented -->
    <p>
        <b>USER POSTED:</b> <?php echo$data['username']?>
    </p>

     <!-- date commented -->
    <p>
      <b>DATE POSTED:</b> <?php echo$data['date']?>
    </p>
    
     <!-- blog title -->
    <p>
        <b>TITLE:</b> <?php echo$data['blog_title']?>
    </p>

     <!-- blog content 'description' -->
    <p>
        <b>DESCRIPTION:</b> <?php echo$data['blog_content']?>
    </p>

     <!-- blog category -->
    <p>
        <b>CATEGORY:</b> <?php echo$data['blog_cat']?>
    </p>

    <!-- show image uploaded -->
    <img src="<?php echo$data['blog_pic']?>" width="400" height="300" alt="profile">
    <hr>

    <?php
    }
    $result->free();
    ?>
  <!-- ---------------------------- -->





    <!-- ------------- SHOW ALL COMMENTS   --------------- -->
    <?php
    
    $query = "SELECT username, comment, datetime FROM COMMENTS  WHERE blogid=".$_GET['idno'];
    $result = $conn->query($query);

    while($data = $result->fetch_assoc()){

    ?>
    <div style="background-color: whitesmoke; padding:1rem; margin:1rem 0; max-width: fit-content;">
    <p>
       <b>USER: </b> <?php echo $data['username'];?>
    </p>
    <p>
        <?php 
                $date = date_create($data['datetime']);
                $date = date_format($date, 'M d, Y') ." at ". date_format($date, 'h:i a');
                
                echo $date;
        ?>
    </p>
    <p>
        <?php echo $data['comment'];?>
    </p>
    </div>
    
    <?php
    }
    $result->free();
    ?>
    <!-- ---------------------------- -->




<!-- ------------- POSTING A COMMENT   --------------- -->

    <!-- the form -->
    <form method="post">
        <textarea name="input" id="" cols="30" rows="10" placeholder="say anything"></textarea>
        <button type="submit" name="comment">Post</button>
    </form>
     <!-- -->

    <?php
    #inserting comment to database
    if(isset($_POST['comment'])){
        $username = getUsername();
        $comment = $_POST['input'];

        $query = "INSERT INTO COMMENTS (username, blogid, datetime, comment)".
                 "VALUES ('$username','$blogid',LOCALTIMESTAMP(),'$comment')";

        mysqli_query($conn, $query);
        header('Location: comment.php?idno='.$blogid);
    }
    
    #select user account
    function getUsername(){
        require 'config.php';
        $query = "SELECT username FROM ACCOUNTS WHERE status='active'";
        $result = $conn->query($query);
        while($data = $result->fetch_assoc()){
            return $data['username'];
        }
        $result->free();
    }
    ?>
<!-- ---------------- -->

</body>
</html>