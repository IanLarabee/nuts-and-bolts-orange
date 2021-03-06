<?php
    require_once "include/connect.php";

    session_start();

    $firstName = "";
    $username = "";

    //Array for user errors
    $errors = array('username'=>'', 'password'=>'', 'confirm' =>'', 'empty'=>'');

    //If user submits form
    if(isset($_POST['register'])) {

        //Store their responses 
        $firstName = $_POST['firstName'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        //Username requirements
        $uUpperCase = preg_match('@[A-Z]@', $username);
        $uSpaces = preg_match("/\\s/", $username);

        //Password requirements
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        //If any fields are empty, give error
        if(empty($firstName) || empty($username) || empty($password) || empty($confirm))
        {
            $errors['empty'] = "All fields are required";
        } 
        //Check username requirements
        else if ($uUpperCase || $uSpaces)
        {
            $errors['username'] = "Username should only be lowercase with no spaces";
        }
        //Check password requirements
        else if (!$uppercase || !$lowercase || !$number || !$specialChars)
        {
            $errors['password'] = "Password should include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        }
        else
        {
            $u_sql = "SELECT * FROM employees WHERE username='$username'";
            $result = mysqli_query($conn, $u_sql);

            //If username already exists, give error
            if (mysqli_num_rows($result) > 0)
            {
                $errors['username'] = "That username is taken";
            }
            //If passwords don't match, give error
            else if ($password !== $confirm)
            {
                $errors['confirm'] = "Passwords do not match";
            }
            //Enter user in DB
            else
            {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $sql = "INSERT INTO employees(username, password) VALUES('$username', '$hash')";
            }
        }
    }
?>