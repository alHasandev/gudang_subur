<?php

session_start();

// hapus session login
session_unset();
session_destroy();

header('Location: login.php');
