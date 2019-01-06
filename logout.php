<?php
unset($_SESSION['userlogin'], $_SESSION['userpassword']);
session_destroy();
Header("Location: index.html");
?>