<?php
if(!BLL\AdminBLL::isLogIn()) {
    header("HTTP/1.1 302 Redirect");
    header("Location: index.php");
    exit;
}
