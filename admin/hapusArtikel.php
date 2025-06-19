<?php
include '../database/koneksi.php';
header('Content-Type: application/json');

$idArtikel = $_POST['idArtikel'];

$artikel = $koneksi->query("SELECT * FROM artikel WHERE id_artikel='$idArtikel'")->fetch_assoc();
if (!empty($artikel['foto'])) {
    $fotoPath = '../../' . $artikel['foto'];
    if (file_exists($fotoPath)) {
        unlink($fotoPath);
    }
}

$hapus = $koneksi->query("DELETE FROM artikel WHERE id_artikel='$idArtikel'");

if ($hapus) {
    echo json_encode([
        "status" => "success",
        "message" => "Artikel berhasil dihapus."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menghapus artikel."
    ]);
}
