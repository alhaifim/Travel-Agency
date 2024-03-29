<?php
include_once 'resource/session.php';
include_once 'resource/Database.php';
include_once 'resource/utilities.php';
session_start();
if(isset($_POST['loginBtn']))
{
//array to hold errors
$form_errors = array();
//validate 
$required_fields = array('CustUserName', 'Password');

 //call the function to check empty field and merge the return data into form_error array
 $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

 if(empty($form_errors))
    {
    //Collect form data
    $currentuser =  $_POST['CustUserName'];  
    $password = $_POST['Password'];
    //Check if user exist in the database
    $sqlQuery = "SELECT * FROM customers_1 WHERE CustUserName = :CustUserName";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array(':CustUserName' => $currentuser));

    while($row = $statement -> fetch())
        {
            $id = $row['CustomerId'];
            $hashed_password = $row['Password'];
            $username= $row['CustUserName'];
            if(password_verify($password, $hashed_password))
                {
                    $_SESSION['CustomerId']=$id;
                    $_SESSION['CustUserName'] = $username;
                    header("location:index.php");
                }
            else
            {
                $result = "<p style='color: red; padding: 20px; border: 1px solid gray;'> Invalid Username or Password</p>";
            }
        }

    }
else
    {
        if(count($form_errors)==1)
        {
            $result="<p style='color: red;'> Invalid username or password </p>"; 
        }
        else
            {
            $result="<p style='color: red;'> Invalid username or password </p>";   
            }
             
    }

}

?>

<?php    
$page_title ="User Authentication - Login Page";
 include_once 'partials/header.php';
 ?>
<body style="padding: 200px;" >


<div class="container text-align: center;">
     <section class = "col col-lg-7">
     <h3>Login Form</h3>
     <?php
        if(isset($result)) echo $result;
        if(!empty($form_errors)) echo show_errors($form_errors); 
     ?>
<form method="post" action="">
  <div class="form-group">
    <label for="usernameField">Username</label>
    <input type="text" class="form-control" id="usernameField" name="CustUserName" placeholder="Username">
  </div>
  <div class="form-group">
    <label for="passwordField">Password</label>
    <input type="password" class="form-control" id="passwordField" placeholder="Password" name="Password">
  </div>
  
  <div class="checkbox">
    <label>
      <input type="checkbox" name="remember"> Remeber Me
    </label>
  </div>
  <a href="forgot_password.php">Forgot Password? </a>
  <button type="submit" class="btn btn-primary float-right" name="loginBtn">Sign in</button>
  <p> <a href="index.php"> Back</a></p>   
</form>
     </section>
</div>
 

<?php include_once 'footer.php'?>  

