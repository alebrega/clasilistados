<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/init.php");
include $crypt->decrypt($_REQUEST["imagen"]);
?>