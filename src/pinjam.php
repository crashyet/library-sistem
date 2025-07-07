<?php
session_start();
$user = $_SESSION['user'];
$peminjam = $_POST['nama'];
$judul_buku = $_POST['judul'];
$author = $_POST['author'];

$books = json_decode(file_get_contents('../data/buku.json'), true);
$pinjaman = json_decode(file_get_contents('../data/pinjam.json'), true);
$antrian = json_decode(file_get_contents('../data/queue.json'), true);

foreach ($books as &$book) {
    if ($book['title'] === $judul_buku) {
        if ($book['available'] === true) {
            $book['available'] = false;
            $pinjaman[] = [
                "user" => $user,
                "peminjam" => $peminjam,
                "book_id" => $book['id'],
                "judul" => $book['title'],
                "author" => $author
            ];
            file_put_contents('../data/buku.json', json_encode($books, JSON_PRETTY_PRINT));
            file_put_contents('../data/pinjam.json', json_encode($pinjaman, JSON_PRETTY_PRINT));
        } else {
            $found = false;
            foreach ($antrian as &$entry) {
                if ($entry['book_id'] == $book['id']) {
                    if (!in_array($user, $entry['queue'])) {
                        $entry['queue'][] = $user;
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $antrian[] = [
                    "book_id" => $book['id'],
                    "judul" => $book['title'],
                    "author" => $author,
                    "queue" => [$user]
                ];
            }
            file_put_contents('../data/queue.json', json_encode($antrian, JSON_PRETTY_PRINT));
        }
        break;
    }
}

// echo $user;
// echo $peminjam;
// echo $judul_buku;
// echo $author;
sleep(1);
header('Location: antrian.php');