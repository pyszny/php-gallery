<?php require_once("includes/header.php"); ?>

<?php

if($session->is_signed_in()) {                          //if is_signed_in === true
    redirect("index.php");
}

if(isset($_POST['submit'])) {
    $username = trim($_POST['username']);               //trim — Strip whitespace (or other characters) from the beginning and end of a string
    $password = trim($_POST['password']);

    //Method to check database user

    $user_found = User::verify_user($username, $password);      //zwraca pierwszy obiekt tablicy, czyli user id


    if($user_found) {                                              //if true (argument exists)
        $session->login($user_found);                              //zapisuje w sesji ID usera, ustawia signed_in na true
        redirect("index.php");                             //...i przenosi do strony index.php
    } else {
        $the_message = "Your password or username are incorrect.";
    }
} else {
    $the_message = "";
    $username = "";
    $password = "";
}



?>

<div class="col-md-4 col-md-offset-3">

    <h4 class="bg-danger"></h4><?php echo $the_message; ?> </h4>

<form id="login-id" action="" method="post">

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username" value="<?php echo htmlentities($username); ?>" >

    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" value="<?php echo htmlentities($password); ?>">

    </div>


    <div class="form-group">
        <input type="submit" name="submit" value="Submit" class="btn btn-primary">

    </div>


</form>


</div>
