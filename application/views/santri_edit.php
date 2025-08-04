<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    <?= form_open_multipart('santri/update/' . $santri->no_kartu) ?>

        <div class="form-group">
            <label>No Kartu (readonly)</label>
            <input type="text" class="form-control" name="no_kartu" value="<?= $santri->no_kartu ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama Santri</label>
            <input type="text" class="form-control" name="nama_santri" value="<?= $santri->nama_santri ?>" required>
        </div>

        <div class="form-group">
            <label>Tempat Lahir</label>
            <input type="text" class="form-control" name="tempat_lahir" value="<?= $santri->tempat_lahir ?>">
        </div>

        <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" class="form-control" name="tanggal_lahir" value="<?= $santri->tanggal_lahir ?>">
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control"><?= $santri->alamat ?></textarea>
        </div>

        <div class="form-group">
            <label>Kamar</label>
            <select name="id_kamar" class="form-control" required>
                <option value="">-- Pilih Kamar --</option>
                <?php foreach ($kamar as $k): ?>
                    <option value="<?= $k->id_kamar ?>" <?= ($k->id_kamar == $santri->id_kamar) ? 'selected' : '' ?>>
                        <?= $k->kamar ?> (<?= $k->tingkat ?>) - <?= $k->nama_walikamar ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Tingkat Sekolah</label>
            <select name="tingkat_sekolah" class="form-control" required>
                <option value="">-- Pilih Tingkat --</option>
                <option value="MTS" <?= $santri->tingkat_sekolah == 'MTS' ? 'selected' : '' ?>>MTS</option>
                <option value="MA" <?= $santri->tingkat_sekolah == 'MA' ? 'selected' : '' ?>>MA</option>
                <option value="SMK" <?= $santri->tingkat_sekolah == 'SMK' ? 'selected' : '' ?>>SMK</option>
            </select>
        </div>

        <div class="form-group">
            <label>Foto Santri</label><br>

            <?php if (!empty($santri->foto)): ?>
                <img src="<?= base_url('uploads/foto_santri/' . $santri->foto) ?>" width="80" class="img-thumbnail mb-2"><br>
            <?php else: ?>
                <span class="text-danger">Belum ada foto.</span><br>
            <?php endif; ?>

            <input type="file" name="foto" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti.</small>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="<?= base_url('santri') ?>" class="btn btn-secondary">Batal</a>

    </form>
</div>
