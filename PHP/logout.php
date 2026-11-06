<?php
require_once __DIR__ . '/auth.php';
terminarSessao(ConnectDB());
header('Location: ' . URL_LOGIN);
exit;