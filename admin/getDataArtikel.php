<?php
include '../database/koneksi.php';

$idArtikel=$_POST['idArtikel'];
$artikelRaw=$koneksi->query("SELECT * FROM artikel WHERE id_artikel='".$idArtikel."'");
$artikel=$artikelRaw->fetch_assoc();

echo json_encode($artikel);