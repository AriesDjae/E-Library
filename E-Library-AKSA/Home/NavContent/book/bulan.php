<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "e-library");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Data buku Bulan Tere Liye
$book = [
    'id_kategori' => 1, // Ganti dengan ID kategori yang sesuai
    'penulis' => 'Tere Liye',
    'judul' => 'Bulan',
    'penerbit' => 'PENERBIT SABAK GRIP',
    'tahun_terbit' => 2022,
    'lokasi_rak' => 'Rak A1', // Tambahkan lokasi rak
    'stok' => 10,
    'deskripsi' => 'Petualangan Raib, Seli, dan Ali berlanjut. Beberapa bulan setelah peristiwa klan bulan, Miss Selena akhirnya muncul di sekolah. Ia membawa kabar menggembirakan untuk anak-anak yang berjiwa petualang seperti Raib, Seli, dan Ali. Miss Selena bersama dengan Av akan mengajak mereka untuk mengunjungi klan matahari selama dua minggu. Av berencana akan bertemu dengan ketua konsil klan matahari, yang menguasai klan matahari sepenuhnya untuk mencari sekutu dalam menghadapi Tamus yang diperkirakan akan bebas dan juga membebaskan raja tanpa mahkota. Sesampainya mereka di Klan matahari, mereka disambut oleh festival bunga matahari. Hal yang tidak pernah disangka oleh Av dan Miss Selena adalah ketua konsil klan matahari yang meminta Raib, Seli, Ali, dan Ily untuk menjadi peserta ke-10 dari festival bunga matahari. Setelah perdebatan yang amat panjang, akhirnya rombongan Raib menerima tawaran itu. Dengan kekuatan yang dimiliki Seli, Raib, dan Ily ditambah dengan senjata berupa panah dan pemukul, mereka bertekad melewati rintangan yang sangat membahayakan keselamatannya itu. Di akhir kisah, Ily telah pergi selama-lamanya meninggalkan keluarganya di Klan bulan juga teman-teman seperjuangannya selama sembilan hari menemukan bunga matahari. Dan Fala, ketua klan matahari telah masuk ke portal ke penjara bayangan bawah tanah. Setelah Serial pertamanya, Bumi, sukses melejit, kini Tere Liye menghadirkan kisah lanjutannya dengan Bulan. Kini anak istimewa itu bernama Seli, masih 15 tahun. Sama halnya seperti remaja yang lain, Seli mendengarkan lagu-lagu yang sedang hits, pergi ke gerai fast food, menonton serial drama dan film. Perbedaannya, Seli bisa mengeluarkan petir. Dan dengan kekuatan itu, Seli bertualang menuju tempat-tempat yang menakjubkan bersama Raib. Dengan bekal hewan tunggangan empat ekor harimau salju sebagai kendaraan mereka selama festival, mereka menghadapi medan berbahaya dan binatang buas dalam hutan hutan yang siap menyerang mereka. Namun, mengapa harus mereka yang mengikuti festival berbahaya ini.',
    'cover_image' => 'src/img/bulan.jpg', // Ganti dengan jalur gambar sampul yang sesuai
];

// Fungsi untuk menyimpan buku ke database
function saveBookToDatabase($conn, $book) {
    $stmt = $conn->prepare("
        INSERT INTO buku (ID_Kategori, Penulis, Judul, Penerbit, Tahun_Terbit, Lokasi_Rak, Stok, Deskripsi, Cover_Image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param(
        "isssissis", 
        $book['id_kategori'], 
        $book['penulis'], 
        $book['judul'], 
        $book['penerbit'], 
        $book['tahun_terbit'], 
        $book['lokasi_rak'], 
        $book['stok'], 
        $book['deskripsi'], 
        $book['cover_image']
    );
    
    if ($stmt->execute()) {
        echo "Buku berhasil disimpan!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Panggil fungsi untuk menyimpan buku
saveBookToDatabase($conn, $book);

$conn->close();
?>
