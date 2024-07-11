<script>
    $(document).ready(function() {
    // Fungsi untuk melakukan AJAX request
    function fetchData() {
        $.ajax({
            url: '/singkron', // Ganti dengan URL endpoint Anda
            type: 'GET',
            success: function(data) {
                // Lakukan sesuatu dengan data yang diterima
                console.log('Data diterima:', data);
            },
            error: function(xhr, status, error) {
                // Tangani kesalahan jika permintaan gagal
                console.error('Kesalahan AJAX:', status, error);
            }
        });
    }

    // Jalankan fetchData pertama kali
    fetchData();

    // Set interval untuk menjalankan fetchData setiap 5 menit
    setInterval(fetchData, 1 * 60 * 1000); // 5 menit * 60 detik * 1000 milidetik
});

</script>
<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
  <p class="text-muted mb-1 mb-md-0">Copyright © 2023 <a href="https://www.nustrastudio.com" target="_blank">Nustra Studio</a></p>
</footer>