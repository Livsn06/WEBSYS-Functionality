<?php
    session_start();
    if(!$_SESSION["user"] &&! $_SESSION["pass"]){
        session_abort();
        header('Location: login.php');
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>

    <!-- -------- links ----------- -->
    <ul>
        <?php
            require 'config.php';

            $query = "SELECT username FROM ACCOUNTS WHERE status='active'";
            $result = $conn-> query($query);

            #get username
            while($data = $result->fetch_assoc()){
        ?>

            <a href="create.php?username=<?php echo $data['username'];?>"><li>Create</li></a>
            <a href="home.php?logout=<?php echo $data['username'];?>"><li>Logout</li></a>

        <?php
            }

            #indicate user account logout
            if(isset($_GET['logout'])){

                    session_destroy();
                    $user = $_GET['logout'];
                    require 'config.php';
                    $query = "UPDATE ACCOUNTS SET status = 'inactive' WHERE username='".$user."'";
                    mysqli_query($conn, $query);
                    header("Location: login.php");
            }
        ?>
            
    </ul> 
    <br><br>
    <!-- ----- -->


    <?php
        require 'config.php';
        $query = "SELECT username,  blogid, blog_title, blog_content, datetime_created, blog_cat, blog_pic FROM POST";
        $result = $conn->query($query);

        while($data = $result->fetch_assoc()){
    ?>

    <div style="background-color: whitesmoke; padding:1rem; margin:1rem 0; max-width: fit-content;">
        <p>
        <b>USER:</b> <?php echo$data['username']?>
        </h4>
        <p>
            <?php 
                $date = date_create($data['datetime_created']);
                $date = date_format($date, 'M d, Y') ." at ". date_format($date, 'h:i a');
                
                echo $date;
            ?>
        </p>
        <p>
           <b>CATEGORY:</b> <?php echo $data['blog_cat'] ?>
        </p>
        <br>
        <h4>
            <a href="comment.php?idno=<?php echo$data['blogid']?>">
                <?php echo$data['blog_title']?>
            </a>
        </h4>
        <p>
            <?php echo$data['blog_content']?>
        </p>

        <img src="<?php echo$data['blog_pic']?>" width="400" height="300" alt="profile">
    </div>

    <?php
    }
    $result->free();

    ?>
</body>

</html>


