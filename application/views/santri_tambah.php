<div class="container-fluid">

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger mb-3"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success mb-3"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <h1 class="h3 mb-4 text-gray-800"><?= isset($title)?$title:'Tambah Santri' ?></h1>

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
            <textarea name="alamat" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label>Kamar</label>
            <select name="id_kamar" id="id_kamar" class="form-control" required>
                <option value="">-- Pilih Kamar --</option>
                <?php foreach ($kamar as $k): ?>
                    <option
                        value="<?= $k->id_kamar ?>"
                        data-wali-id="<?= isset($k->id_walikamar)?$k->id_walikamar:'' ?>"
                        data-wali-nama="<?= isset($k->nama_walikamar)?$k->nama_walikamar:'' ?>">
                        <?= $k->kamar ?> (<?= $k->tingkat ?>) - <?= isset($k->nama_walikamar)?$k->nama_walikamar:'-' ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Wali kamar akan otomatis terisi sesuai pilihan kamar.</small>
        </div>

        <input type="hidden" name="id_walikamar" id="id_walikamar" value="">
        <div class="form-group">
            <label>Wali Kamar</label>
            <input type="text" class="form-control" id="wali_nama" value="" readonly placeholder="(Akan terisi otomatis)">
        </div>

        <div class="form-group">
            <label>Tingkat Sekolah</label>
            <select name="tingkat_sekolah" class="form-control" required>
                <option value="">-- Pilih Tingkat --</option>
                <option value="MTS">MTS</option>
                <option value="SMK">SMK</option>
                <option value="MA">MA</option>
            </select>
        </div>

        <div class="form-group">
            <label>Foto Santri</label>
            <input type="file" name="foto" class="form-control">
            <small class="form-text text-muted">Maks 2MB. Tipe: jpg/jpeg/png.</small>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= site_url('santri') ?>" class="btn btn-secondary">Kembali</a>

    </form>

    <!-- SCRIPT: auto set wali dari pilihan kamar -->
    <script>
        (function () {
            const selKamar  = document.getElementById('id_kamar');
            const hidWaliId = document.getElementById('id_walikamar');
            const waliNama  = document.getElementById('wali_nama');

            if (selKamar) {
                selKamar.addEventListener('change', function () {
                    const opt = selKamar.options[selKamar.selectedIndex];
                    const walId = opt.getAttribute('data-wali-id') || '';
                    const walNm = opt.getAttribute('data-wali-nama') || '';
                    hidWaliId.value = walId;
                    waliNama.value  = walNm;
                });
            }
        })();
    </script>

    <!-- SCRIPT POLLING UID DAFTAR (tetap seperti punyamu) -->
    <script>
        let lastUID = "";
        const inputUID = document.getElementById('no_kartu');

        setInterval(function () {
            fetch("<?= base_url('api/get_uid_daftar') ?>")
                .then(r => r.json())
                .then(data => {
                    const uid = (data && data.uid ? String(data.uid) : '').trim();
                    if (!uid) return;

                    if (uid !== lastUID && (!inputUID.value || inputUID.value === '[]')) {
                        lastUID = uid;
                        inputUID.value = uid;
                        console.log("✅ UID daftar:", uid);

                        // kosongkan agar tidak nyangkut
                        fetch("<?= base_url('api/reset_uid_daftar') ?>");
                    }
                })
                .catch(()=>{});
        }, 1000);
    </script>

</div>
