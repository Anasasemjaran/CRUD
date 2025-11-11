<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

$sql_select = "SELECT foto FROM kendaraan WHERE id = $id";
$result = mysqli_query($conn, $sql_select);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $foto_to_delete = $row['foto'];

    $sql_delete = "DELETE FROM kendaraan WHERE id = $id";
    
    if (mysqli_query($conn, $sql_delete)) {
        $target_dir = 'uploads/';
        if ($foto_to_delete != '' && file_exists($target_dir . $foto_to_delete)) {
            unlink($target_dir . $foto_to_delete);
        }
    } else {
        echo 'Error: ' . mysqli_error($conn);
        exit;
    }
}

header('Location: index.php');
exit;
?>