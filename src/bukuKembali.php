<?php
session_start();
$user = $_SESSION['user'];
$bookId = $_POST['book_id'];

$pinjamFile = '../data/pinjam.json';
$antrianFile = '../data/queue.json';
$bukuFile = '../data/buku.json';

// Ambil semua data
$pinjam = json_decode(file_get_contents($pinjamFile), true);
$antrian = json_decode(file_get_contents($antrianFile), true);
$books = json_decode(file_get_contents($bukuFile), true);

// 1. Hapus data pinjaman user
foreach ($pinjam as $i => $p) {
    if ($p['user'] === $user && $p['book_id'] == $bookId) {
        unset($pinjam[$i]);
        break;
    }
}
$pinjam = array_values($pinjam); // reset index

// 2. Cek antrian buku tersebut
foreach ($antrian as $i => &$antri) {
    if ($antri['book_id'] == $bookId && !empty($antri['queue'])) {
        $nextUser = array_shift($antri['queue']); // Ambil user pertama

        // Ambil detail buku
        $judul = $antri['judul'];
        $author = $antri['author'];

        // Tambah ke data pinjaman baru
        $pinjam[] = [
            "user" => $nextUser,
            "peminjam" => "-", // Ganti sesuai kebutuhan
            "book_id" => $bookId,
            "judul" => $judul,
            "author" => $author
        ];

        // Jika antrian kosong setelahnya, hapus objek antrian
        if (empty($antri['queue'])) {
            unset($antrian[$i]);
        }

        break;
    } elseif ($antri['book_id'] == $bookId && empty($antri['queue'])) {
        unset($antrian[$i]);
    }
}
$antrian = array_values($antrian); // reset index

// 3. Tandai buku jadi available jika tidak diberikan ke antrian
$dipinjam = false;
foreach ($pinjam as $p) {
    if ($p['book_id'] == $bookId) {
        $dipinjam = true;
        break;
    }
}
foreach ($books as &$book) {
    if ($book['id'] == $bookId) {
        $book['available'] = !$dipinjam;
        break;
    }
}

// Simpan kembali
file_put_contents($pinjamFile, json_encode($pinjam, JSON_PRETTY_PRINT));
file_put_contents($antrianFile, json_encode($antrian, JSON_PRETTY_PRINT));
file_put_contents($bukuFile, json_encode($books, JSON_PRETTY_PRINT));

// Redirect ke halaman pinjaman lagi
sleep(1);
header('Location: antrian.php');
exit();
?>
