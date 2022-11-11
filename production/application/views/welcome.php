<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>The Wall</title>

    <style>.hidden{ display: none; } </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div class="welcome">
        <h1>The Wall</h1>

    <?php if($this->uri->segment(1) == 'login'){ 
        include 'application/views/partials/form_login.php';
    } else{
        include 'application/views/partials/form_registration.php';
    } ?>
    </div>
</body>
</html>