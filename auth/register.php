<?php
$file = '../data/users.json';

// mengambil data dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // kode validasi
    if (empty($email) || empty($password)) {
        echo "Email dan password wajib diisi!";
        exit;
    }

    // read data dari json
    $data = json_decode(file_get_contents(filename: $file), true);

    // cek apakah email sudah terdaftar
    foreach ($data as $user) {
        if ($user['email'] === $email) {
            echo "Email sudah terdaftar!";
            exit;
        }
    }

    // Tambahkan user baru
    $data[] = [
        'username' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    // Simpan kembali ke JSON
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

    echo "<script>alert('Registrasi berhasil');</script>";
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Novel Grove</title>

    <link rel="stylesheet" href="../css/tailwind.css" />
  </head>
  <body>
    <div
      class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 flex items-center justify-center p-4"
    >
      <div class="w-full max-w-md">
        <div class="text-center mb-8">
          <div
            class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full mb-4 shadow-lg"
          >
            <div
              class="bg-gradient-to-r from-blue-600 to-blue-800 p-2 rounded-lg inline-flex items-center justify-center w-16 h-16"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="30"
                height="30"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-book-open h-6 w-6 text-white"
              >
                <path d="M12 7v14"></path>
                <path
                  d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"
                ></path>
              </svg>
            </div>
          </div>
          <h1 class="text-3xl font-bold text-gray-800 mb-2">
            Bergabung Dengan Kami
          </h1>
          <p class="text-gray-600">Buat akun baru untuk memulai</p>
        </div>
        <div
          class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-blue-500"
        >
          <div class="flex mb-6 bg-gray-100 rounded-lg p-1">
            <button
              class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 text-gray-600 hover:text-blue-500"
              onclick="window.location.href = 'login.php';"
            >
              Masuk
            </button>
            <button
              class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200 bg-blue-500 text-white shadow-md"
            >
              Daftar
            </button>
          </div>
          <div class="transition-all duration-300">
            <form class="space-y-6" method="POST" action="register.php">
              <div class="space-y-2">
                <label
                  class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-gray-700 font-medium"
                  for="name"
                  >Nama Lengkap</label
                >
                <div class="relative">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-user absolute left-3 top-3 h-5 w-5 text-gray-400"
                  >
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle></svg
                  ><input
                    type="text"
                    class="flex w-full rounded-md border bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-10 h-12 border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                    id="name"
                    name="name"
                    placeholder="Nama lengkap Anda"
                    required=""
                    value=""
                  />
                </div>
              </div>
              <div class="space-y-2">
                <label
                  class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-gray-700 font-medium"
                  for="email"
                  >Email</label
                >
                <div class="relative">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-mail absolute left-3 top-3 h-5 w-5 text-gray-400"
                  >
                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                    <path
                      d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"
                    ></path></svg
                  ><input
                    type="email"
                    class="flex w-full rounded-md border bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-10 h-12 border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                    id="email"
                    name="email"
                    placeholder="nama@email.com"
                    required=""
                    value=""
                  />
                </div>
              </div>
              <div class="space-y-2">
                <label
                  class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-gray-700 font-medium"
                  for="password"
                  >Password</label
                >
                <div class="relative">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-lock absolute left-3 top-3 h-5 w-5 text-gray-400"
                  >
                    <rect
                      width="18"
                      height="11"
                      x="3"
                      y="11"
                      rx="2"
                      ry="2"
                    ></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg
                  ><input
                    type="password"
                    class="flex w-full rounded-md border bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-10 pr-10 h-12 border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                    id="password"
                    name="password"
                    placeholder="Buat password yang kuat"
                    required=""
                    value=""
                  /><button
                    type="button"
                    class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 transition-colors"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-eye h-5 w-5"
                    >
                      <path
                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"
                      ></path>
                      <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="space-y-2">
                <label
                  class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-gray-700 font-medium"
                  for="confirmPassword"
                  >Konfirmasi Password</label
                >
                <div class="relative">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-lock absolute left-3 top-3 h-5 w-5 text-gray-400"
                  >
                    <rect
                      width="18"
                      height="11"
                      x="3"
                      y="11"
                      rx="2"
                      ry="2"
                    ></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg
                  ><input
                    type="password"
                    class="flex w-full rounded-md border bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm pl-10 pr-10 h-12 border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                    id="confirmPassword"
                    name="confirmPassword"
                    placeholder="Ulangi password Anda"
                    required=""
                    value=""
                  /><button
                    type="button"
                    class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 transition-colors"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="lucide lucide-eye h-5 w-5"
                    >
                      <path
                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"
                      ></path>
                      <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="flex items-start space-x-2">
                <input
                  id="terms"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mt-1"
                  required=""
                /><label for="terms" class="text-sm text-gray-600"
                  >Saya setuju dengan
                  <button
                    type="button"
                    class="text-blue-500 hover:text-blue-600 hover:underline"
                  >
                    Syarat &amp; Ketentuan
                  </button>
                  dan
                  <button
                    type="button"
                    class="text-blue-500 hover:text-blue-600 hover:underline"
                  >
                    Kebijakan Privasi
                  </button></label
                >
              </div>
              <button
                class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary hover:bg-primary/90 px-4 py-2 w-full h-12 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02]"
                type="submit"
              >
                Daftar Sekarang
              </button>
              <div class="relative">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                  <span class="px-4 bg-white text-gray-500"
                    >Atau daftar dengan</span
                  >
                </div>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <button
                  type="button"
                  class="flex items-center justify-center px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                >
                  <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <path
                      fill="#4285f4"
                      d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                    ></path>
                    <path
                      fill="#34a853"
                      d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                    ></path>
                    <path
                      fill="#fbbc05"
                      d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                    ></path>
                    <path
                      fill="#ea4335"
                      d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                    ></path></svg
                  >Google</button
                ><button
                  type="button"
                  class="flex items-center justify-center px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                >
                  <svg class="w-5 h-5 mr-2" fill="#1877f2" viewBox="0 0 24 24">
                    <path
                      d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                    ></path></svg
                  >Facebook
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="text-center mt-6">
          <p class="text-gray-500 text-sm">
            Sudah punya akun?
            <button
              class="text-blue-500 hover:text-blue-600 font-medium hover:underline transition-colors"
              onclick="window.location.href = 'login.php';"
            >
              Masuk di sini
            </button>
          </p>
        </div>
      </div>
    </div>
  </body>
</html>
