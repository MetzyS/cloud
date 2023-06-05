<?php
session_start();
session_unset();
header('Location: /www/cloud/public/home/connect/');
exit;
