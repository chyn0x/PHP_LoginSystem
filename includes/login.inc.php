<?php

if (isset($_POST['login-submit'])){
    require 'dbh.inc.php';

    $mailuid= $_POST['mailuid'];
    $password= $_POST['pwd'];

    if(empty($mailuid) || empty($password)){
        header("Location ../index.php?error=emptyfields");
        exit();
    }
    else{

        $sql = "SELECT FROM users WHERE uidUsers=?;";
        $stmt= mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($stmt,$sql)){
             header("Location ../index.php?error=sqlerror");
            exit();
            }
                else{
                    mysql_stmt_bind_param($stmt,"s",$mailuid);
                    mysql_stmt_execute($stmt);
                    $result= mysqli_stmt_get_result($stmt);
                    if($row = mysqli_fetch_assoc($result)){

                        $pwdcheck=password_verify($password,$row['pwdUsers']);
                        if($pwdcheck == false){
                             header("Location ../index.php?error=wrongpwd");
                            exit();
                        }
                        elseif( $pwdcheck==true){
                            session_start();
                            $_SESSION['userId']= $row['idUsers'];
                            $_SESSION['userUid']= $row['uidUsers'];
                               header("Location ../index.php?login=success");
                            exit();

                        }
                        else{
                              header("Location ../index.php?error=wrongpwd");
                                exit();
                        }
                    }
                    else{
                         header("Location ../index.php?error=nouser");
                        exit();
                    }


                }
        }
    }

else{
    header("Location ../index.php");
    exit();
}