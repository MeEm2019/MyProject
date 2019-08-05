<?php
    
    // Starting the session
    
          session_start();

          ?>
<!DOCTYPE html>

<html lang="en">

<head>
          <meta charset="utf-8">
         

   <link rel="stylesheet" href="sign_in.css">

          <title>Sign-in</title>

</head>
<body>
<?php

          // Verifying if the member is already signed-in
    
        
                    if (isset($_SESSION['pseudonym']) || isset($_COOKIE['pseudonym'])){
                              echo 'You re already in';
                              header("Location: home.php");
                              return;
                    }

include ('bar.php');
        
        
        // Checking if the member is new or not
          if (isset($_SESSION['new'])){
          if ($_SESSION['new']){
                    echo 'congrutalations you just created your account now you need to sign-in' . $_SESSION['new'];
          }
          
          }
        
?>

<!-- The sign in method -->

<form method="post">
    <!-- Giving a general error in case if the email exists or if the password is correct -->
    
          <p id="error" style="display: none">The email given doesn't exist or the password is incorrect</p><br>
    
    <!-- E-mail -->

          <label for="email">E-mail :</label> <input type="email" name="email" id="email" value="<?php
    //If the value has been given we store it
    if (isset($_POST['email'])){

              echo $_POST['email'];

}
    ?>"><br>

   <!-- Password -->
    
          <label for="password">Password :</label><input type="password" name="password" id="password"><br>
    
    <!-- This checkbox verify if the member want to be rememberd -->
    
          <label for="remember_me">Remember me :</label><input type="checkbox" id="remember_me" name="remember_me">
          
          <input type="submit" value="sign-in">
    <p>Don't have an account <a href="sign_up.php">Sign-up</a> </p>
</form>

<?php
          // The php code will only work if the all values were entred this is the second verification
    
          if (isset($_POST['email']) && isset($_POST["password"])){
              
              // Connecting to the database
              
          try{
                    $db = new PDO('mysql:host=localhost;dbname=members_area;charset=utf8' , 'root' , '');
                    
          }
          
          catch (Exception $exception){
                    die('Error : ' . $exception->getMessage());
                    
          }
          
          // Looking for the member that has th email giving in the database
          
          $member_sign_in = $db->prepare('SELECT * FROM members WHERE email= :email');
          $member_sign_in->execute(array('email' => $_POST['email']));
         
         // If the member doesn't exist send the genral error
          if (!$member_sign_in){
                    ?>
                        <script type="text/javascript">
                                  document.getElementById("error").style.display = "block";
                        </script>
<?php
        return;
          }
          
          // Taking the information from the database
    
          $information_got = $member_sign_in->fetch();
          
          // Hashing th password with the special tachnic
    
          $password = 'Docreg' . $_POST['password'] . 'gercoD';
          
          // Verifying the password given and the password in the database
    
          if (!password_verify($password , $information_got['password'])){
              // If the passwords mismatch send the general error
    
                    ?>
<script type="text/javascript">
    document.getElementById("error").style.display = "block";
        
          </script>
<?php
                    return;
          }
          else{
              // Verifying the remember checkbox
              
              
              
              if (isset($_POST["remember_me"])){
                  // If checked save the pseudonyw as a cookie
                    setcookie('pseudonym' , $information_got['pseudonym'] , time() + 3600 * 60  * 30 , null , null , false ,true);
                    
                   
              }
          else{
              // If not savibg the pseudonym as session
              
                         $_SESSION['pseudonym'] = $information_got['pseudonym'];
           
           }
          // Rediricting the member to the home page
           
        header('Location: home.php');
           
          }
          
          }
  ?>
</body>
</html>

