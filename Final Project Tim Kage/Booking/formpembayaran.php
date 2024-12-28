<!-- Formulir Pendaftaran Booking -->
<div class="container-custom mt-5 pt-5">
    <h2 class="text-center mb-4">Pendaftaran Booking</h2>
    
        <form action="" method="POST" enctype="multipart/form-data">
            <!-- Sopir -->
            <div class="mb-3">
                <label for="sopir" class="form-label">Pakai Sopir / Tidak</label>
                <select class="form-select" id="sopir" name="sopir" required>
                    <option value="">Pilih opsi</option>
                    <option value="iya">Iya</option>
                    <option value="tidak">Tidak</option>
                </select>
            </div>

        <!-- Masa Sewa -->
        <div class="mb-3">
            <label for="masaSewa" class="form-label">Masa Sewa</label>
            <select class="form-select" id="masaSewa" name="masaSewa" required onchange="updateTanggalSelesai()">
                <option value="">Pilih masa sewa</option>
                <option value="1">1 Hari</option>
                <option value="2">2 Hari</option>
                <option value="3">3 Hari</option>
                <option value="4">4 Hari</option>
                <option value="5">5 Hari</option>
                <option value="6">6 Hari</option>
                <option value="7">7 Hari</option>
            </select>   
        </div>

        <!-- Paket Sewa -->
        <div class="mb-3">
            <label for="paketSewa" class="form-label">Paket Sewa</label>
            <select class="form-select" id="paketSewa" name="paketSewa" required onchange="updateTotalHarga()">
                <option value="">Pilih paket sewa</option>
                <option value="6">6 Jam (Rp <?= number_format($harga6jam, 0, ',', '.'); ?>)</option>
                <option value="12">12 Jam (Rp <?= number_format($harga12jam, 0, ',', '.'); ?>)</option>
                <option value="24">24 Jam (Rp <?= number_format($harga24jam, 0, ',', '.'); ?>)</option>
            </select>
        </div>

        <!-- Tanggal Mulai -->
        <div class="mb-3">
            <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" id="tanggalMulai" name="tanggalMulai" required onchange="updateTanggalSelesai()" min="<?php echo date('Y-m-d'); ?>">
        </div>

        <!-- Tanggal Selesai -->
        <div class="mb-3">
            <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
            <input type="text" class="form-control" id="tanggalSelesai" name="tanggalSelesai" readonly>
        </div>

        <!-- Waktu Pengambilan -->
        <div class="mb-3">
            <label for="waktuPengambilan" class="form-label">Waktu Pengambilan</label>
            <input type="time" class="form-control" id="waktuPengambilan" name="waktuPengambilan" required>
        </div>

        <!-- Metode Pembayaran -->
        <div class="mb-3">
            <label for="payment" class="form-label">Metode Pembayaran</label>
            <select class="form-select" id="payment" name="payment" required onchange="updatePaymentDetails()">
                <option value="" selected>Pilih metode pembayaran</option>
                <option value="dana">Pembayaran via Dana</option>
                <option value="mandiri">Pembayaran via Bank Mandiri</option>
                <option value="gopay">Pembayaran via Gopay</option>
            </select>
        </div>
        
        <!-- Informasi Tujuan Pembayaran -->
        <div class="mb-3" id="paymentDetails" style="display: none;">
            <label class="form-label">Tujuan Pembayaran</label>
            <div class="border p-3 rounded bg-light">
                <p><strong>Nama Tujuan:</strong> <span id="paymentName"></span></p>
                <p><strong>Nomor Rekening/ID:</strong> <span id="paymentNumber"></span></p>
            </div>
        </div>

        <!-- Total Harga -->
        <div class="mb-3">
            <label for="totalHarga" class="form-label">Total Harga</label>
            <p id="totalHarga" class="form-control">Rp 0</p>
        </div>

        <!-- Unggah Bukti Transfer -->
        <div class="mb-3">
            <label for="bukti" class="form-label">Unggah Bukti Transfer</label>
            <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*,application/pdf" required>
        </div>
        
        <button type="submit" class="btn btn-primary" name="submit">Booking</button>
    </form>
</div>