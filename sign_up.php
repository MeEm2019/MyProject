<?php
    //Starting th session
          session_start();
          // Verifying if the member is already signed-in
          
        
                    if (isset($_SESSION['pseudonym']) || isset($_COOKIE['pseudonym'])){
                              include("logout_user.php");
                              return;
                    }
          include "bar.php";
         
?>
<!DOCTYPE html>
<html lang="en">

<head>
          <meta charset="utf-8">
          <link rel="stylesheet" href="sign_up.css">
          <link rel="stylesheet" href="general_style.css">
    <link rel="icon" href="rlogo.png">
          <title>Sign-up</title>

</head>
<body>

<!--
Form containing the information that will be saved into the database :
   -Pseudonym of the user
   -E-mail of the user
   -Password
-->
<form method="post"   >
    <!-- Pseudonym -->
    
          <label for="pseudonym">Pseudonym :</label> <input id="pseudonym" type="text" name="pseudonym" value="<?php
    //If the value has been given we store it
    if (isset($_POST['pseudonym'])){

        echo $_POST['pseudonym'];

    }
    ?>" required><br>
    
    <!--- The message that will be display if the pseudonym is already used -->
    
          <p id="pseudonym_error" style="display: none">Sorry this pseudonym is already used</p><br>
    
    <!-- E-mail -->
    
          <label for="email">E-mail :</label><input id="email" type="email" name="email" value="<?php
    //If the value has been given we store it
    if (isset($_POST['email'])){

        echo $_POST['email'];

    }
    ?>" required><br>
    
    <!-- The message that will be display if the e-mail is already used -->
    
          <p id="email_error" style="display: none">This email is used by another account</p><br>
    
    <!-- Password -->
    
          <label for="password">Password :</label><input id="password" type="password" name="password" required><br>
    
    <!-- The message that will be display if the password doesn't match with the password confirmation -->
    
          <p id="password_error" style="display: none">Remember that password necessit at least one number and one special character </p><br>
    
    <!-- Password confirmation -->
          <label for="password_confirmation">Password confirmation :</label><input id="password_confirmation" type="password" name="password_confirmation" required><br>
          <input type="submit" value="Sign-up">
    
    
    <p>Already have an account ? <a href="sign_in.php">Sign-in</a> </p>
</form>
<?php
    
          
         
          
          // The php code will only work if the all values were entred this is the second verification
    
          if (isset($_POST['pseudonym']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirmation']) ) {
          
                    // Connecting to the database
          
                    try{
                              $db = new PDO('mysql:host=localhost;dbname=members_area;charset=utf8','root','');
                    }
                    catch (Exception $exception){
                              die('Error : ' . $exception->getMessage());
                    }
                    // Getting the information from the database
          
                    $anwer = $db->query('SELECT pseudonym ,email FROM members');
               
                  while ($data = $anwer->fetch()){
                 
                    // Verifying if the pseudonym exists
                    
                            if ($data['pseudonym'] == $_POST['pseudonym']){
                                
                                    // If yes displaying the error
                                     ?>
                                          <script type="text/javascript">
                                                    document.getElementById("pseudonym_error").style.display = "block";
                                          </script>
<?php
                                      return;
                            }
          
                    // Verifying if the email exists
              
                            if ($data['email'] == $_POST['email']){
                                
                                    // If yes displaying the error
    
                             ?>
                            <script type="text/javascript">
                                document.getElementById("email_error").style.display = "block";
                            </script>
<?php
                                      return;
                            }
                  
        }
        
        // Verifying the match between the two password
        
      if(!preg_match('#[a-z]+[A-z]+[0-9]+[!?&.;:,]#', $_POST['password'])){
          
                  ?>
                  
                  <script type="text/javascript">
                      document.getElementById("password_error").style.display = "block";
                  </script>
                    
                      <?php
                
                  return;
                  
        }
      
      
        if ($_POST['password'] != $_POST['password_confirmation']){
        ?>
                      <script type="text/javascript">
                      alert("The passwords mismatch")
                  </script>
<?php
                  return;
        }
        
        
       
        // Hashing the password by a special method
        
        $password = 'Docreg' . $_POST['password'] . 'gercoD';
        $password = password_hash($password, PASSWORD_DEFAULT);
        // Insering the information and creating the user
        
        $insertion = $db->prepare('INSERT INTO members(pseudonym , email , password , registration_date) VALUES(:pseudonym , :email , :password , NOW()) ');
        $insertion->execute(array(
                      'pseudonym' => $_POST['pseudonym'],
                      'email' => $_POST['email'],
                      'password' => $password
        ));
        
        
        // Giving the new variable a true value for indicating if the member is new or not
        $_SESSION['new'] = true;
        
        // Rediricting the member to the sign-in page
          
          header('Location: sign_in.php');
 }?>

</body>





</html>




