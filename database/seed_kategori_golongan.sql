INSERT IGNORE INTO kategori_barang (kode, nama) VALUES
('K001', 'Obat Oral / Tablet'),
('K002', 'Obat Injeksi / Vial / Ampul'),
('K003', 'Salep / Gel / Topikal'),
('K004', 'Balutan Luka Modern (Modern Dressing)'),
('K005', 'Cairan Antiseptik & Pencuci Luka'),
('K006', 'Alat Medis Habis Pakai (BMHP)'),
('K007', 'Kasa & Perban Khusus'),
('K008', 'Jarum & Spuit (Syringe)'),
('K009', 'Infus & Aksesoris Terapi');

INSERT IGNORE INTO golongan_barang (kode, nama) VALUES
('G001', 'Obat Bebas (Hijau)'),
('G002', 'Obat Bebas Terbatas (Biru)'),
('G003', 'Obat Keras (Merah / K)'),
('G004', 'Narkotika & Psikotropika'),
('G005', 'Obat Wajib Apotek (OWA)'),
('G006', 'Alat Kesehatan Habis Pakai'),
('G007', 'Suplemen & Nutrisi Medis');

INSERT IGNORE INTO kodesatuan (kode_sat, satuan) VALUES
('PCS', 'Pieces / Satuan'),
('BOX', 'Box / Kotak / Dus'),
('BTL', 'Botol / Botol Kaca'),
('TUB', 'Tube / Salep'),
('STR', 'Strip / Blister'),
('TAB', 'Tablet / Kaplet'),
('AMP', 'Ampul'),
('VIAL', 'Vial'),
('ROL', 'Roll / Gulung'),
('LBR', 'Lembar / Sheet'),
('PAK', 'Pack / Kantong'),
('SET', 'Set / Perangkat');

