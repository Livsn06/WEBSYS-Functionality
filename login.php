
<?php
require 'config.php';

# identify if login already [else it direct to log in page]
session_start();
if(isset($_SESSION["user"])){
    
    if((!empty($_SESSION['user']) && !empty($_SESSION['pass']))){
        header('Location: home.php');
    }else{
        session_destroy();
    }
 }
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <!-- ------------ LOG IN FORM ---------------- -->
    <form action="login.php" method="post">

        <label for="Email">Log in</label><br><br>
        <input type="text" placeholder="username" name="username" required><br>
        <input type="password" placeholder="password" name="password" required><br><br>

        <button type="submit" name="submit" value="submit">Register user</button>
    </form>
    <!-- ---------- -->
</body>
</html>


<?php

if(isset($_POST['submit'])){
  $user =  $_POST['username'];
  $pass =  $_POST['password'];
    $_SESSION['user'] = $user;
    $_SESSION['pass'] = $pass;  

    $pass = hash('sha256', $pass);//<-- use sha256 rather than md5
    $account_Exist =false;


    # login existing account
    $result = $conn-> query("SELECT username, password, status FROM ACCOUNTS");
    while($data = $result->fetch_assoc()){
        if($data['username'] == $user && $data['password'] == $pass){
            accountStatus($data['username']);
            header("Location: home.php");
            $account_Exist = true; break;
        }
    }

    # if account doest exist [it automatically add to database]
    echo " <script>alert('Account created');</script>";
    if(!$account_Exist){
            $query = "INSERT INTO ACCOUNTS (username, password, status) VALUES ('$user','$pass','inactive')";
            mysqli_query($conn, $query);
            $account_Exist = false;
    }
}

#make account active 
function accountStatus($user){
    require 'config.php';
    $query = "UPDATE ACCOUNTS SET status = 'active' WHERE username='".$user."'";
    mysqli_query($conn, $query); 
}

?>