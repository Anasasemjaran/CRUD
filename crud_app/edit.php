<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$message = '';

if (isset($_POST['submit'])) {
    $merk = $_POST['merk'];
    $model = $_POST['model'];
    $tahun = $_POST['tahun'];
    $harga = $_POST['harga'];
    $old_foto = $_POST['old_foto'];

    $new_foto = $old_foto; 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_name = $_FILES['foto']['name'];
        $target_dir = 'uploads/';
        $target_file = $target_dir . basename($foto_name);

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $new_foto = $foto_name; 

            if ($old_foto != '' && $old_foto != $new_foto && file_exists($target_dir . $old_foto)) {
                unlink($target_dir . $old_foto);
            }
        } else {
            $message = 'Gagal mengunggah berkas baru!';
        }
    }

    if ($message === '') {
        $sql = "UPDATE kendaraan SET merk = '$merk', model = '$model', tahun = '$tahun', harga = '$harga', foto = '$new_foto' WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            header('Location: index.php');
            exit;
        } else {
            $message = 'Error: ' . mysqli_error($conn);
        }
    }
}

$sql_select = "SELECT * FROM kendaraan WHERE id = $id";
$result = mysqli_query($conn, $sql_select);

if (mysqli_num_rows($result) === 0) {
    header('Location: index.php');
    exit;
}

$kendaraan = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kendaraan</title>
</head>
<body>
    <h2>Edit Kendaraan: <?php echo $kendaraan['merk'] . ' ' . $kendaraan['model']; ?></h2>

    <?php if ($message): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="old_foto" value="<?php echo htmlspecialchars($kendaraan['foto']); ?>">
        
        <div>
            <label for="merk">Merk:</label>
            <input type="text" id="merk" name="merk" value="<?php echo htmlspecialchars($kendaraan['merk']); ?>" required>
        </div>
        
        <div>
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($kendaraan['model']); ?>" required>
        </div>

        <div>
            <label for="tahun">Tahun:</label>
            <input type="number" id="tahun" name="tahun" value="<?php echo htmlspecialchars($kendaraan['tahun']); ?>" required>
        </div>
        
        <div>
            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" step="0.01" value="<?php echo htmlspecialchars($kendaraan['harga']); ?>" required>
        </div>
        
        <div>
            <label>Foto Saat Ini:</label><br>
            <?php if ($kendaraan['foto']): ?>
                <img src="uploads/<?php echo htmlspecialchars($kendaraan['foto']); ?>" width="100"><br>
                <span><?php echo htmlspecialchars($kendaraan['foto']); ?></span>
            <?php else: ?>
                <span>Tidak ada foto saat ini.</span>
            <?php endif; ?>
        </div>
        
        <div>
            <label for="foto">Ganti Foto (Kosongkan jika tidak ingin ganti):</label>
            <input type="file" id="foto" name="foto" accept="image/*">
        </div>
        
        <button type="submit" name="submit">Simpan Perubahan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>