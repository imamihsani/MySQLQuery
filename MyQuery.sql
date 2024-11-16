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
  -- KALAU DI TABEL UTAMA KOLOM YG MAU DIHUBUNGIN KE TABEL ANAK BUKAN PK
  ALTER TABLE ticketapps.pesanan
  ADD CONSTRAINT unique_kode_pesanan UNIQUE (kode_pesanan);
  -- BATAL
  ALTER TABLE msa_web.win_proyek_kronologis 
  DROP FOREIGN KEY fk_kronologis_proyek;
--

-- LIHAT DATA DUPLIKAT 
  SELECT COUNT(CustomerID), Country -- yg mau dicari adalah kolom Country ada berapa data yang sama berdasarkan customer id yang jadi primary key
  FROM Customers -- ini tabel
  GROUP BY Country -- ini kolom
  HAVING COUNT(CustomerID) > 2; -- ini bisa diatur , contoh ini misal mau nyari data yang sama lebih dari 2
--
