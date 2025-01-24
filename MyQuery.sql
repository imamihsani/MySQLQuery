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
  -- BATAL UNIQUE FK
  ALTER TABLE ticketapps.history_pengerjaan DROP INDEX unique_kode_pesanan;
  -- BATAL FK
  ALTER TABLE msa_web.win_proyek_kronologis 
  DROP FOREIGN KEY fk_kronologis_proyek;
--

-- LIHAT DATA DUPLIKAT 
  SELECT COUNT(CustomerID), Country -- yg mau dicari adalah kolom Country ada berapa data yang sama berdasarkan customer id yang jadi primary key
  FROM Customers -- ini tabel
  GROUP BY Country -- ini kolom
  HAVING COUNT(CustomerID) > 2; -- ini bisa diatur , contoh ini misal mau nyari data yang sama lebih dari 2
--

-- BIKIN KOLOM KOLOM YANG SAMA IKUT BERUBAH KALAU TABEL INDUK DIUBAH BERDASARKAN KOLOM YANG JADI KEY/UNIQUE (SYARAT: TABEL ANAK HARUS PUNYA KOLOM YANG NAMANYA SAMA)
    DELIMITER $$
    CREATE TRIGGER update_invoice_after_pesanan_update
    AFTER UPDATE ON pesanan --tabel induk
    FOR EACH ROW
    BEGIN
    -- Nentuin kolom-kolom di tabel induk yang mau dijadiin acuan/trigger
    IF OLD.berat != NEW.berat 
        OR OLD.jenis != NEW.jenis 
        OR OLD.layanan != NEW.layanan 
        OR OLD.delivery != NEW.delivery 
        OR OLD.tgl_pemesanan != NEW.tgl_pemesanan THEN
      
        UPDATE invoice --tabel anak
        SET 
            tanggal_masuk = NEW.tgl_pemesanan, -- kanan kolom tabel anak, kiri kolom tabel induk
            berat = NEW.berat,
            jenis = NEW.jenis,
            layanan = NEW.layanan,
            delivery = NEW.delivery
        WHERE kode_pesanan = NEW.kode_pesanan; -- berdasarkan value ini (pastiin unik)
    END IF;
    END$$
    DELIMITER ;
--

-- MIGRASI/COPY/MINDAH DATA DARI TABEL A KE TABEL B
  INSERT INTO nama_database.tabel_b (proyek_id, item, tanggal_start_aktual, tanggal_end_aktual) --kolom kolom tabel b yang mau buat nampung value dari tabel a
  SELECT 
      id AS proyek_id, -- kiri tabel a, kanan tabel b
      'Mitra' AS item, -- Contoh pendefinisian nama kolom jadi value 
      tanggal_start_mitra AS tanggal_start_aktual, 
      tanggal_end_mitra AS tanggal_end_aktual
  FROM 
      nama_database.tabel_a;
--
--
INSERT INTO msa.eng_proyek_team (proyek_id, role, member, tanggal_start, tanggal_end)
SELECT 
    p.id AS proyek_id,
    'Drafter' AS role,
    k.recid AS member,
    p.tanggal_start_drafter AS tanggal_start,
    p.tanggal_end_drafter AS tanggal_end
FROM 
    msa.proyek_copy p
LEFT JOIN 
    msa.`hrd - karyawan` k ON k.nama = p.nama_drafter;
--
--
INSERT INTO msa.eng_proyek_team (proyek_id, role, member, tanggal_start, tanggal_end)
SELECT 
    p.id AS proyek_id,
    'Waspang' AS role,
    NULLIF(TRIM(BOTH ',' FROM CONCAT(
        CASE WHEN k1.recid IS NOT NULL THEN k1.recid ELSE '' END,
        CASE WHEN k2.recid IS NOT NULL THEN CONCAT(',', k2.recid) ELSE '' END,
        CASE WHEN k3.recid IS NOT NULL THEN CONCAT(',', k3.recid) ELSE '' END
    )), '') AS member,
    p.tanggal_start_waspang AS tanggal_start,
    p.tanggal_end_waspang AS tanggal_end
FROM 
    msa.proyek_copy p
LEFT JOIN 
    msa.`hrd - karyawan` k1 ON k1.nama = p.nama_waspang
LEFT JOIN 
    msa.`hrd - karyawan` k2 ON k2.nama = p.nama_waspang2
LEFT JOIN 
    msa.`hrd - karyawan` k3 ON k3.nama = p.nama_waspang3;
--
