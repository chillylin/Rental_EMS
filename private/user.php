<?php

    function getUser($db, $username, $password){

        $query = 'SELECT * FROM users WHERE username ="'.$username.'" AND userpass = "'.$password.'"';

        return mysqli_query($db,$query);

    }

    

?>