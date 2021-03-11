<?php
    require_once "config/connect.php";

    session_start();

    $firstname = '';
    $lastname = '';
    $username = '';
    $password = '';
   
    $errors = array('firstname'=>'', 'lastname'=>'', 'username'=>'', 'password'=>'');

    if(isset($_POST['submit'])) {
        if(empty($_POST['firstname'])) {
            $errors['username'] = 'Please enter your first name';
        } else {
            $username = $_POST['firstname'];
            if(!preg_match('/^[\s\S]{1,255}$/', $firstname)){
                $errors['firstname'] = 'The first name must be no longer than 255 characters';
            }
        }

    if(isset($_POST['submit'])) {
        if(empty($_POST['lastname'])) {
            $errors['lastname'] = 'Please enter your lastname';
        } else {
            $username = $_POST['lastname'];
            if(!preg_match('/^[\s\S]{1,255}$/', $lastname)){
                $errors['lastname'] = 'The last name must be no longer than 255 characters';
            }
        }  

    if(isset($_POST['submit'])) {
        if(empty($_POST['username'])) {
            $errors['username'] = 'Please enter your username';
        } else {
            $username = $_POST['username'];
            if(!preg_match('/^[\s\S]{1,255}$/', $username)){
                $errors['username'] = 'The username must be no longer than 255 characters';
            }
        }

        if(isset($_POST['submit'])) {
            if(empty($_POST['password'])) {
                $errors['password'] = 'Please enter your password';
            } else {
                $name = $_POST['password'];
                if(!preg_match('/(?=.\d)(?=.[a-z])(?=.[A-Z]).{8,20}/', $password)){
                    $errors['password'] = 'The password must be 8 to 20 characters long';
                }
            }

     if(!array_filter($errors)) {
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        }
