<?php

require_once "./User.php";
session_start();

class Userpdo extends User {}

$dummy = new Userpdo();

$dummy->getAllInfos();
$dummy->getEmail();

// $dummy->register("Tigor", "abc123", "tig@gmail.com", "Nina", "Penot");
$dummy->connect("Tigor", "abc123");
$dummy->getAllInfos();
$dummy->update("Tigora", "abc123", "tiga@gmail.com", "Nina", "Penot");
$dummy->getAllInfos();
$dummy->disconnect();
$dummy->getEmail();
