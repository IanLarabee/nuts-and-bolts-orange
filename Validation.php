<?php
    require_once "config/connect.php";

    session+start();

    $username = '';
    $password = '';

    $errors = array('username'=>'', 'password'=>'');

    if(isset($_POST['submit'])) {
        if(empty($_POST['username'])) {
            $errors['username'] = 'Please enter your username';
        } else {
            $name = $_POST['username'];
            if(!preg_match('/^[\s\S]{1,255}$/', $username)){
                $errors['username'] = 'The username must be no longer than 255 characters';
            }
        }

        if(isset($_POST['submit'])) {
            if(empty($_POST['password'])) {
                $errors['username'] = 'Please enter your password';
            } else {
                $name = $_POST['password'];
                if(!preg_match('(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}', $password)){
                    $errors['password'] = 'The password must be 8 or more characters long';
                }
            }

     if(!array_filter($errors)) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);