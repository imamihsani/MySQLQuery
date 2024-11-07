-- UPDATE WHERE
  UPDATE nama_database.`nama_tabel`
  SET `kolom_yg_mau_diubah` = 'diganti ke ini'
  WHERE `kolom_yg_mau_diubah` = 'misalnya ini';
-- 

-- BIKIN FOREIGN KEY DARI TABEL ANAK KE TABEL UTAMA
  ALTER TABLE msa_web.win_proyek_kronologis -- tabel anak
  ADD CONSTRAINT fk_proyek_kronologis
  FOREIGN KEY (proyek_id) REFERENCES msa_web.win_proyek(proyek_id) -- ini tabel induk/utama
  ON DELETE CASCADE
  ON UPDATE CASCADE;
  -- BATAL
  ALTER TABLE msa_web.win_proyek_kronologis 
  DROP FOREIGN KEY fk_kronologis_proyek;
--
