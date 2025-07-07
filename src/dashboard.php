<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

// List buku
$books = json_decode(file_get_contents('../data/buku.json'), true);

// Inisialisasi Stack
if (!isset($_SESSION['stack'])) {
    $_SESSION['stack'] = [];
    $_SESSION['top'] = -1;
}

function isEmpty() {
    return $_SESSION['top'] == -1;
}

function isFull() {
    $maxStack = 10;
    return $_SESSION['top'] == $maxStack - 1;
}

function push($data) {
    $maxStack = 10;

    // Cek duplikat, hapus dulu biar pindah ke paling atas
    $existingIndex = array_search($data, $_SESSION['stack']);
    if ($existingIndex !== false) {
        pop($existingIndex);
    }

    if (isFull()) {
        // Geser semua ke kiri
        for ($i = 0; $i < $_SESSION['top']; $i++) {
            $_SESSION['stack'][$i] = $_SESSION['stack'][$i + 1];
        }
        $_SESSION['stack'][$_SESSION['top']] = $data;
    } else {
        $_SESSION['top']++;
        $_SESSION['stack'][$_SESSION['top']] = $data;
    }
}

function pop($index) {
    for ($i = $index; $i < $_SESSION['top']; $i++) {
        $_SESSION['stack'][$i] = $_SESSION['stack'][$i + 1];
    }
    unset($_SESSION['stack'][$_SESSION['top']]);
    $_SESSION['top']--;
}

// Variabel kontrol
$showDropdown = false;
$hasilFilter = []; // Tetap kosong di awal

// Proses Pencarian
if (isset($_POST['cari'])) {
    $judul = trim($_POST['judul']);
    if ($judul !== "") {
        push($judul);
        $showDropdown = true;

        foreach ($books as $buku) {
            if (stripos($buku['title'], $judul) !== false) {
                $hasilFilter[] = $buku;
            }
        }
    } else {
        // Jika tidak ada pencarian, tampilkan semua buku
        $hasilFilter = $books;
    }
} else {
    // Jika tidak ada pencarian, tampilkan semua buku
    $hasilFilter = $books;
}

// Hapus Riwayat Per Item
if (isset($_POST['hapus'])) {
    $index = $_POST['index'];
    pop($index);
    $showDropdown = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital - Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="h-full">
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo">
                    <div class="logo-icon">📚</div>
                    <h1>Perpustakaan Digital</h1>
                </div>
            </div>
            <div class="header-right">
                <button class="notification-btn">🔔</button>
                <div class="user-menu">
                    <div class="user-avatar"><?= trim(substr($_SESSION['user'], 0, 1)) ?></div>
                    <span class="user-name"><?= $_SESSION['user'] ?></span>
                    <span class="dropdown-arrow">▼</span>
                </div>
            </div>
        </div>
    </header>

    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="#" class="nav-item">
                    <span class="nav-icon">📚</span>
                    <span class="nav-text">Katalog Buku</span>
                </a>
                <a href="antrian.php" class="nav-item">
                    <span class="nav-icon">📖</span>
                    <span class="nav-text">Peminjaman</span>
                </a>
                <a href="#" class="nav-item">
                    <span class="nav-icon">👥</span>
                    <span class="nav-text">Anggota</span>
                </a>
                <a href="#" class="nav-item">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">Laporan</span>
                </a>
            </nav>

            <div class="recent-activity">
                <h3>Aktivitas Terbaru</h3>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-dot green"></div>
                        <span>Buku baru ditambahkan</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot blue"></div>
                        <span>5 peminjaman hari ini</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot orange"></div>
                        <span>2 buku terlambat</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <p class="stat-title">Total Buku</p>
                            <p class="stat-value">2,847</p>
                        </div>
                        <div class="stat-icon blue">📚</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <p class="stat-title">Buku Dipinjam</p>
                            <p class="stat-value">1,234</p>
                        </div>
                        <div class="stat-icon green">📖</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <p class="stat-title">Anggota Aktif</p>
                            <p class="stat-value">856</p>
                        </div>
                        <div class="stat-icon purple">👥</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-info">
                            <p class="stat-title">Buku Populer</p>
                            <p class="stat-value">127</p>
                        </div>
                        <div class="stat-icon orange">📈</div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="search-section">
                <div class="search-container">
                    <form method="POST" class="search-form" id="search-area">
                        <div class="search-input-container">
                            <span class="search-icon">🔍</span>
                            <input type="text" name="judul" id="search-input" class="search-input" placeholder="Cari buku, penulis, atau genre..." autocomplete="off" value="<?= isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : '' ?>">
                        </div>
                        <button type="submit" name="cari" class="search-btn">Cari</button>
                    </form>

                    <!-- Search History Dropdown -->
                    <?php if (!isEmpty()): ?>
                        <div class="search-history" id="search-history" style="<?= $showDropdown ? 'display: block;' : 'display: none;' ?>">
                            <div class="history-header">
                                <span>Riwayat Pencarian</span>
                                <button type="button" class="close-history" onclick="closeHistory()">✕</button>
                            </div>
                            <?php for ($i = $_SESSION['top']; $i >= 0; $i--): ?>
                                <div class="history-item">
                                    <button type="button" class="history-query" onclick="selectHistory('<?= htmlspecialchars($_SESSION['stack'][$i]) ?>')">
                                        <span class="history-icon">🕒</span>
                                        <?= htmlspecialchars($_SESSION['stack'][$i]) ?>
                                    </button>
                                    <form method="POST" style="margin: 0;">
                                        <input type="hidden" name="index" value="<?= $i ?>">
                                        <button type="submit" name="hapus" class="delete-history">✕</button>
                                    </form>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>

                    <div class="filters">
                        <select class="filter-select">
                            <option value="all">Semua Genre</option>
                            <option value="fiksi">Fiksi</option>
                            <option value="sastra">Sastra</option>
                            <option value="romansa">Romansa</option>
                            <option value="fantasi">Fantasi</option>
                        </select>
                        <select class="filter-select">
                            <option value="title">Urutkan: Judul</option>
                            <option value="author">Urutkan: Penulis</option>
                            <option value="year">Urutkan: Tahun</option>
                            <option value="rating">Urutkan: Rating</option>
                        </select>
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid">⊞</button>
                            <button class="view-btn" data-view="list">☰</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Header -->
            <div class="results-header">
                <h2>
                    <?= isset($_POST['judul']) && $_POST['judul'] ? 'Hasil pencarian "' . htmlspecialchars($_POST['judul']) . '"' : 'Semua Buku' ?>
                    <span class="results-count">(<?= count($hasilFilter) ?> buku)</span>
                </h2> <br>
                
                <?php if (!empty($hasilFilter)): ?>
                    <div class="books-grid" id="books-container">
                        <?php foreach ($hasilFilter as $book): ?>
                            <div class="book-card">
                                <div class="book-cover-container">
                                    <img src="<?= $book['cover'] ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="book-cover">
                                    <?php if (!$book['available']): ?>
                                        <div class="unavailable-overlay">
                                            <span class="unavailable-badge">Tidak Tersedia</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="book-info">
                                    <div class="book-header">
                                        <h3 class="book-title"><?= htmlspecialchars($book['title']) ?></h3>
                                        <div class="book-rating">
                                            <span class="star">⭐</span>
                                            <span class="rating-value"><?= $book['rating'] ?></span>
                                        </div>
                                    </div>
                                    <p class="book-author"><?= htmlspecialchars($book['author']) ?></p>
                                    <span class="book-genre"><?= htmlspecialchars($book['genre']) ?></span>
                                    <p class="book-description"><?= htmlspecialchars($book['description']) ?></p>
                                    <div class="book-meta">
                                        <span><?= $book['year'] ?></span>
                                        <span><?= $book['pages'] ?> halaman</span>
                                    </div>
                                    <button 
                                        class="borrow-btn <?= !$book['available'] ? 'disabled' : '' ?>"
                                        data-available="<?= $book['available'] ? '1' : '0' ?>"
                                        data-title="<?= htmlspecialchars($book['title']) ?>" 
                                        data-author="<?= htmlspecialchars($book['author']) ?>">
                                        <?= $book['available'] ? 'Pinjam Buku' : 'Tambah ke Antrian' ?>
                                    </button>
                                </div>  
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Tidak ditemukan hasil untuk pencarian ini.</p>
                <?php endif; ?>
            </div>

            <!-- Navigation Button -->
            <div class="navigation-section">
                <a href="antrian_peminjaman.php" class="queue-button">Lihat Antrian Peminjaman</a>
            </div>
        </main>
    </div>

    <!-- Popup Form Peminjaman -->
    <div id="formPopup" class="formPopup">
        <div class="modal-box">
            <button onclick="closeForm()" class="close-btn">&times;</button>
            <h2>Form Peminjaman Buku</h2>

            <form  method="POST" action="pinjam.php">
                <div>
                    <label for="nama">Nama Peminjam</label>
                    <input type="text" id="nama" name="nama" required placeholder="Masukkan nama">
                </div>
                <div>
                    <label for="judul">Judul Buku</label>
                    <input type="text" id="judul" name="judul" readonly>
                </div>
                <div>
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" readonly>
                </div>
                <button type="submit">Pinjam Sekarang</button>
            </form>
        </div>
    </div>

    <!-- Popup Konfirmasi -->
    <div id="alertAntrian" class="formPopup">
        <div class="modal-box">
            <button onclick="closeAlert()" class="close-btn">&times;</button>
            <h2>Saat ini buku sedang dipinjam.</h2>
            <p>Jika tetap ingin meminjam, silahkan masuk ke antrian.</p>
            <!-- Tombol "Masuk ke Antrian" -->
            <button type="submit" onclick="confirmAntrian()">Masuk ke Antrian</button>
        </div>
    </div>

    <script>

        // Buka form & isi judul otomatis
        function openForm() {
            document.getElementById("formPopup").style.display = "flex";
        }

        function closeForm() {
            document.getElementById("formPopup").style.display = "none";
        }
        
        function openAlert() {
            document.getElementById("alertAntrian").style.display = "flex";
        }

        function closeAlert() {
            document.getElementById("alertAntrian").style.display = "none";
        }

        function confirmAntrian() {
            closeAlert();
            openForm();
        }

        document.querySelectorAll('.borrow-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const available = btn.dataset.available === '1';
                const author = btn.getAttribute("data-author");
                const title = btn.getAttribute("data-title");
                document.getElementById("judul").value = title;
                document.getElementById("author").value = author;


                if (available) {
                    // 📘 Tampilkan form peminjaman
                    openForm();
                } else {
                    // 🚫 Tampilkan notifikasi
                    openAlert();
                }
            });
        });

        window.addEventListener("click", function (e) {
            const modal = document.getElementById("formPopup");
            const content = modal.querySelector(".modal-box");
            if (e.target === modal && !content.contains(e.target)) {
                closeForm();
            }
        });

        function submitForm(e) {
            e.preventDefault();
            const nama = document.getElementById("nama").value;
            const judul = document.getElementById("judul").value;
            const author = document.getElementById("author").value;

            alert(`Data Peminjaman:\nNama: ${nama}\nJudul: ${judul}\nAuthor: ${author}`);
            closeForm();
            e.target.reset();
        }

        const searchInput = document.getElementById('search-input');
        const riwayatDropdown = document.getElementById('search-history');
        const searchArea = document.getElementById('search-area');

        // Pastikan dropdown tersembunyi saat halaman dimuat
        if (riwayatDropdown) {
            riwayatDropdown.style.display = 'none';
        }

        // Tampilkan dropdown ketika input difokus
        searchInput.addEventListener('focus', () => {
            if (riwayatDropdown) {
                riwayatDropdown.style.display = 'block';
            }
        });

        // Sembunyikan dropdown ketika klik di luar area search
        document.addEventListener('click', function(e) {
            if (!searchArea.contains(e.target)) {
                if (riwayatDropdown) {
                    riwayatDropdown.style.display = 'none';
                }
            }
        });

        // View toggle functionality
        const viewButtons = document.querySelectorAll('.view-btn');
        const booksContainer = document.getElementById('books-container');

        viewButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                viewButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                const view = btn.dataset.view;
                if (view === 'list') {
                    booksContainer.classList.add('list-view');
                } else {
                    booksContainer.classList.remove('list-view');
                }
            });
        });
    </script>
</body>
</html>