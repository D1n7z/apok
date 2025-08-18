<?php
    function generateHash($passwd){
        $hash = password_hash($passwd, PASSWORD_BCRYPT);
        return $hash;
    }
?>