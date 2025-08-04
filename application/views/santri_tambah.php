<div class="container-fluid">

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <!-- ✅ GANTI TAG <form> AGAR BISA UPLOAD FOTO -->
    <?= form_open_multipart('santri/simpan') ?>

        <div class="form-group">
            <label>No Kartu RFID</label>
            <input type="text" name="no_kartu" id="no_kartu" class="form-control" readonly placeholder="Tap Kartu RFID">
        </div>

        <div class="form-group">
            <label>Nama Santri</label>
            <input type="text" name="nama_santri" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control">
        </div>

        <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control">
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Kamar</label>
            <select name="id_kamar" class="form-control" required>
                <option value="">-- Pilih Kamar --</option>
                <?php foreach ($kamar as $k): ?>
                    <option value="<?= $k->id_kamar ?>">
                        <?= $k->kamar ?> (<?= $k->tingkat ?>) - <?= $k->nama_walikamar ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Tingkat Sekolah</label>
            <select name="tingkat_sekolah" class="form-control" required>
                <option value="">-- Pilih Tingkat --</option>
                <option value="MTs">MTs</option>
                <option value="MA">MA</option>
                <option value="SMK">SMK</option>
            </select>
        </div>

        <div class="form-group">
            <label>Foto Santri</label>
            <input type="file" name="foto" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <!-- SCRIPT POLLING UID DAFTAR -->
    <script>
        let lastUID = "";

        setInterval(function () {
            fetch("<?= base_url('api/get_uid_daftar') ?>")
                .then(response => response.json())
                .then(data => {
                    const uid = data.uid?.trim();
                    const input = document.getElementById('no_kartu');

                    if (uid && uid !== lastUID && (!input.value || input.value === '[]')) {
                        lastUID = uid;
                        input.value = uid;
                        console.log("✅ UID daftar ditemukan:", uid);

                        // Kosongkan uid_daftar.txt agar tidak tertap dua kali
                        fetch("<?= base_url('api/reset_uid_daftar') ?>");
                    }
                });
        }, 1000);
    </script>
</div>
