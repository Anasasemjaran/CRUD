<?php
include 'db.php';

$message = '';

if (isset($_POST['submit'])) {
    $merk = $_POST['merk'];
    $model = $_POST['model'];
    $tahun = $_POST['tahun'];
    $harga = $_POST['harga'];
    $foto_name = '';

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_name = $_FILES['foto']['name'];
        $target_dir = 'uploads/';
        $target_file = $target_dir . basename($foto_name);
        
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $message = 'Gagal mengunggah berkas!';
            $foto_name = ''; 
        }
    }

    if ($message === '') {
        $sql = "INSERT INTO kendaraan (merk, model, tahun, harga, foto) VALUES ('$merk', '$model', '$tahun', '$harga', '$foto_name')";

        if (mysqli_query($conn, $sql)) {
            header('Location: index.php');
            exit;
        } else {
            $message = 'Error: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kendaraan Baru</title>
</head>
<body>
    <h2>Tambah Kendaraan Baru</h2>

    <?php if ($message): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="merk">Merk:</label>
            <input type="text" id="merk" name="merk" required>
        </div>
        
        <div>
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" required>
        </div>

        <div>
            <label for="tahun">Tahun:</label>
            <input type="number" id="tahun" name="tahun" required>
        </div>
        
        <div>
            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" step="0.01" required>
        </div>
        
        <div>
            <label for="foto">Foto Kendaraan:</label>
            <input type="file" id="foto" name="foto" accept="image/*">
        </div>
        
        <button type="submit" name="submit">Tambah Kendaraan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>