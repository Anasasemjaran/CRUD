<?php
include 'db.php';
// Search
$search = isset($_GET['search']) ? $_GET['search']: '';
// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page']: 1;
$start = ($page - 1) * $limit;
// Count total records
$countSql = "SELECT COUNT(*) AS total FROM kendaraan WHERE merk LIKE '%$search%' OR model LIKE '%$search%'";
$countResult = mysqli_query($conn, $countSql);
$countRow = mysqli_fetch_assoc($countResult);
$total = $countRow['total'];
$pages = ceil($total / $limit);
// Fetch records
$sql = "SELECT * FROM kendaraan WHERE merk LIKE '%$search%' OR model LIKE '%$search%' LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);
?>
<form method='GET'>
    <input type='text' name='search' placeholder='Search by Merk/Model...' value='<?php echo
$search; ?>'>
    <button type='submit'>Search</button>
</form>

<a href='add.php'>+ Tambah Kendaraan Baru</a>
<table border='1' cellpadding='10'>
    <tr>
        <th>ID</th>
        <th>Merk</th>
        <th>Model</th>
        <th>Tahun</th>
        <th>Harga</th>
        <th>Foto</th>
        <th>Aksi</th>
    </tr>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['merk']; ?></td>
        <td><?php echo $row['model']; ?></td>
        <td><?php echo $row['tahun']; ?></td>
        <td><?php echo $row['harga']; ?></td>
        <td><img src='uploads/<?php echo $row['foto']; ?>' width='80'></td>
        <td>
            <a href='edit.php?id=<?php echo $row['id']; ?>'>Edit</a> |
            <a href='delete.php?id=<?php echo $row['id']; ?>'>Delete</a>
        </td>
    </tr>
<?php } ?>
</table>

<?php for ($i = 1; $i <= $pages; $i++) { ?>
    <a href='?page=<?php echo $i; ?>&search=<?php echo $search; ?>'><?php echo $i; ?></a>
<?php } ?>