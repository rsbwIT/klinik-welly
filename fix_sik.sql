USE sik;

-- 1. Hapus foreign key detailjurnal yang error ke rekening
ALTER TABLE detailjurnal DROP FOREIGN KEY detailjurnal_ibfk_2;

-- 2. Hapus foreign key opname yang error ke databarang
ALTER TABLE opname DROP FOREIGN KEY opname_ibfk_1;

-- 3. Hapus foreign key detail_nota_jalan yang error ke akun_bayar
ALTER TABLE detail_nota_jalan DROP FOREIGN KEY detail_nota_jalan_ibfk_2;

SELECT 'BERHASIL_DIPERBAIKI' AS STATUS;
