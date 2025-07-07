<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: ../auth/login.php');
  exit;
}
$userAktif = $_SESSION['user'] ?? null;

// ambil data dari json
$buku = json_decode(file_get_contents('../data/buku.json'), true);
$pinjaman = json_decode(file_get_contents('../data/pinjam.json'), true);
$antrian = json_decode(file_get_contents('../data/queue.json'), true);

if (!is_array($pinjaman)) $pinjaman = [];
if (!is_array($antrian)) $antrian = [];


// Filter berdasarkan user login
$totalDipinjam = 0;
foreach ($pinjaman as $p) {
    if ($p['user'] === $userAktif) $totalDipinjam++;
}

$totalAntrian = 0;
foreach ($antrian as $entry) {
    if (isset($entry['queue']) && in_array($userAktif, $entry['queue'])) {
        $totalAntrian++;
    }
}

$totalBuku = $totalDipinjam + $totalAntrian;

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan Digital - Dashboard</title>
  <link rel="stylesheet" href="../css/tailwind.css">
</head>

<body class="h-full">
  <!-- Header -->
  <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="flex items-center justify-between px-6 py-4 max-w-full">
      <div class="flex items-center">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-xl">
            📚
          </div>
          <h1 class="text-xl font-bold text-slate-800">Perpustakaan Digital</h1>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <button class="bg-transparent border-none text-xl cursor-pointer p-2 rounded-md transition-colors hover:bg-slate-100">
          🔔
        </button>
        <div class="flex items-center gap-2 cursor-pointer p-2 rounded-md transition-colors hover:bg-slate-100">
          <div class="w-8 h-8 bg-slate-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
            <?= trim(substr($_SESSION['user'], 0, 1)) ?>
          </div>
          <span class="font-medium text-gray-700"><?= $_SESSION['user'] ?></span>
          <span class="text-xs text-gray-500">▼</span>
        </div>
      </div>
    </div>
  </header>

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 shadow-sm p-4">
      <nav class="flex flex-col gap-2 mb-8">
        <a href="dashboard.php" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 transition-all font-medium hover:bg-slate-100 hover:text-blue-600">
          <span class="text-lg">🏠</span>
          <span>Dashboard</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 transition-all font-medium hover:bg-slate-100 hover:text-blue-600">
          <span class="text-lg">📚</span>
          <span>Katalog Buku</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-blue-600 bg-blue-50 transition-all font-medium">
          <span class="text-lg">📖</span>
          <span>Peminjaman</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 transition-all font-medium hover:bg-slate-100 hover:text-blue-600">
          <span class="text-lg">👥</span>
          <span>Anggota</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 transition-all font-medium hover:bg-slate-100 hover:text-blue-600">
          <span class="text-lg">📊</span>
          <span>Laporan</span>
        </a>
      </nav>

      <div class="mt-8">
        <h3 class="text-sm font-semibold text-slate-800 mb-3">Aktivitas Terbaru</h3>
        <div class="flex flex-col gap-3">
          <div class="flex items-center gap-3 text-sm text-gray-600">
            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            <span>Buku baru ditambahkan</span>
          </div>
          <div class="flex items-center gap-3 text-sm text-gray-600">
            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
            <span>5 peminjaman hari ini</span>
          </div>
          <div class="flex items-center gap-3 text-sm text-gray-600">
            <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
            <span>2 buku terlambat</span>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <div class="space-y-8">
        <div class="text-center space-y-4">
          <h1 class="text-4xl font-bold text-gray-900">Antrian Buku Saya</h1>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">Lacak posisi Anda dalam antrian dan dapatkan pemberitahuan saat buku siap dipinjam</p>
        </div>

        <!-- statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
  <!-- Buku Dipinjam -->
  <div class="bg-white rounded-xl shadow-sm p-6 border border-blue-100">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-600 mb-1">Buku Dipinjam</p>
        <p class="text-2xl font-bold text-green-600"><?= $totalDipinjam ?></p>
      </div>
      <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
      </div>
    </div>
  </div>

  <!-- Dalam Antrian -->
  <div class="bg-white rounded-xl shadow-sm p-6 border border-blue-100">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-600 mb-1">Dalam Antrian</p>
        <p class="text-2xl font-bold text-blue-600"><?= $totalAntrian ?></p>
      </div>
      <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
    </div>
  </div>

  <!-- Total Buku (global) -->
  <div class="bg-white rounded-xl shadow-sm p-6 border border-blue-100">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-600 mb-1">Total Buku</p>
        <p class="text-2xl font-bold text-gray-900"><?= $totalBuku ?></p>
      </div>
      <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
      </div>
    </div>
  </div>
</div>

        <!-- buku dipinjam -->
        <div class="space-y-4">
          <h2 class="text-2xl font-semibold text-gray-900 flex items-center gap-2">
            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>Buku Dipinjam
          </h2>

          <div class="grid gap-4">
            <?php foreach ($pinjaman as $data): ?>
              <?php if ($data['user'] === $userAktif): ?>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200 group">
                  <div class="flex flex-col sm:flex-row gap-6">
                    <div class="flex-shrink-0">
                      <div class="w-20 h-28 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow duration-300">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="flex-1 space-y-4">
                      <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="space-y-2">
                          <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-700 transition-colors duration-200">
                            <?= htmlspecialchars($data['judul']) ?>
                          </h3>
                          <p class="text-gray-600">by <?= htmlspecialchars($data['author']) ?></p>
                          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border bg-green-100 text-green-800 border-green-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Siap untuk dibaca
                          </span>
                        </div>
                        <!-- Tombol kembalikan -->
                        <form method="post" action="bukuKembali.php">
                          <input type="hidden" name="book_id" value="<?= htmlspecialchars($data['book_id']) ?>">
                          <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2 self-start">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Kembalikan Buku
                          </button>
                        </form>
                      </div>
                      <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 text-green-800">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          <span class="font-medium">Buku anda sudah siap untuk dibaca!</span>
                        </div>
                        <p class="text-green-700 text-sm mt-1">Selamat membaca buku ini! Semoga anda menyukainya.</p>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- menunggu antrian -->
        <div class="space-y-4">
          <h2 class="text-2xl font-semibold text-gray-900">Menunggu Antrian</h2>
          <div class="grid gap-4">
            <?php foreach ($antrian as $buku): ?>
              <?php if (isset($buku['queue']) && in_array($userAktif, $buku['queue'])): ?>
                <?php
                  $posisi = array_search($userAktif, $buku['queue']);
                  $tersisa = $posisi > 0 ? $posisi : 0; // sisa antrian sebelum dia
                ?>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200 group">
                  <div class="flex flex-col sm:flex-row gap-6">
                    <div class="flex-shrink-0">
                      <div class="w-20 h-28 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow duration-300">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                                  C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                          </path>
                        </svg>
                      </div>
                    </div>
                    <div class="flex-1 space-y-4">
                      <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="space-y-2">
                          <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-700 transition-colors duration-200">
                            <?= htmlspecialchars($buku['judul']) ?>
                          </h3>
                          <p class="text-gray-600">by <?= htmlspecialchars($buku['author']) ?></p>
                          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border bg-blue-100 text-blue-800 border-blue-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                              </path>
                            </svg>
                            Menunggu Antrian
                          </span>
                        </div>
                      </div>
                      <div class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                          <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857
                                        M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0
                                        2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                              </svg>
                              Tersisa <?= $tersisa ?> orang
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>

  </script>
</body>
</html>