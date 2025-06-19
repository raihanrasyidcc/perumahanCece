<?php

$host='localhost';
$user='root';
$password='anakke2020';
$database='perumahancece';

$koneksi=mysqli_connect($host,$user,$password,$database);

if(!$koneksi){
    die('Server not connected');
}