package pbo_10;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class BukuService {

    public void createDatabase() {
        String sql = "CREATE DATABASE IF NOT EXISTS perpustakaan";

        try (Connection connection = KoneksiDatabase.getKoneksiServer();
             Statement statement = connection.createStatement()) {
            statement.executeUpdate(sql);
            System.out.println("Database 'perpustakaan' berhasil dipastikan/dibuat.");
        } catch (SQLException e) {
            System.out.println("Gagal membuat database: " + e.getMessage());
        }
    }

    public void createTable() {
        String sql = """
                CREATE TABLE IF NOT EXISTS buku (
                    kode_buku VARCHAR(20) PRIMARY KEY,
                    judul_buku VARCHAR(100),
                    pengarang VARCHAR(100),
                    tahun_terbit INT,
                    stok INT
                )
                """;

        try (Connection connection = KoneksiDatabase.getKoneksiDatabase();
             Statement statement = connection.createStatement()) {
            statement.executeUpdate(sql);
            System.out.println("Tabel 'buku' berhasil dipastikan/dibuat.");
        } catch (SQLException e) {
            System.out.println("Gagal membuat tabel: " + e.getMessage());
        }
    }

    public void insertBuku(Buku buku) {
        String sql = "INSERT INTO buku (kode_buku, judul_buku, pengarang, tahun_terbit, stok) VALUES (?, ?, ?, ?, ?)";

        try (Connection connection = KoneksiDatabase.getKoneksiDatabase();
             PreparedStatement preparedStatement = connection.prepareStatement(sql)) {
            preparedStatement.setString(1, buku.getKodeBuku());
            preparedStatement.setString(2, buku.getJudulBuku());
            preparedStatement.setString(3, buku.getPengarang());
            preparedStatement.setInt(4, buku.getTahunTerbit());
            preparedStatement.setInt(5, buku.getStok());
            preparedStatement.executeUpdate();
            System.out.println("-> Data buku baru berhasil ditambahkan!");
        } catch (SQLException e) {
            System.out.println("Gagal menambahkan data buku: " + e.getMessage());
        }
    }

    public void tampilkanSemuaBuku() {
        String sql = "SELECT kode_buku, judul_buku, pengarang, tahun_terbit, stok FROM buku";

        try (Connection connection = KoneksiDatabase.getKoneksiDatabase();
             PreparedStatement preparedStatement = connection.prepareStatement(sql);
             ResultSet resultSet = preparedStatement.executeQuery()) {

            System.out.println();
            System.out.println("--- DAFTAR KOLEKSI BUKU ---");
            while (resultSet.next()) {
                String kodeBuku = resultSet.getString("kode_buku");
                String judulBuku = resultSet.getString("judul_buku");
                String pengarang = resultSet.getString("pengarang");
                int tahunTerbit = resultSet.getInt("tahun_terbit");
                int stok = resultSet.getInt("stok");

                System.out.println(kodeBuku + " | " + judulBuku + " | " + pengarang
                        + " | Tahun: " + tahunTerbit + " | Stok: " + stok);
            }
        } catch (SQLException e) {
            System.out.println("Gagal menampilkan data buku: " + e.getMessage());
        }
    }

    public void updateStokBuku(String kodeBuku, int stokBaru) {
        String sql = "UPDATE buku SET stok = ? WHERE kode_buku = ?";

        try (Connection connection = KoneksiDatabase.getKoneksiDatabase();
             PreparedStatement preparedStatement = connection.prepareStatement(sql)) {
            preparedStatement.setInt(1, stokBaru);
            preparedStatement.setString(2, kodeBuku);
            int barisDiubah = preparedStatement.executeUpdate();

            if (barisDiubah > 0) {
                System.out.println("Stok buku dengan kode " + kodeBuku + " berhasil diperbarui.");
            } else {
                System.out.println("Data buku dengan kode " + kodeBuku + " tidak ditemukan.");
            }
        } catch (SQLException e) {
            System.out.println("Gagal memperbarui stok buku: " + e.getMessage());
        }
    }

    public void deleteBuku(String kodeBuku) {
        String sql = "DELETE FROM buku WHERE kode_buku = ?";

        try (Connection connection = KoneksiDatabase.getKoneksiDatabase();
             PreparedStatement preparedStatement = connection.prepareStatement(sql)) {
            preparedStatement.setString(1, kodeBuku);
            int barisDihapus = preparedStatement.executeUpdate();

            if (barisDihapus > 0) {
                System.out.println("Buku dengan kode " + kodeBuku + " berhasil dihapus.");
            } else {
                System.out.println("Data buku dengan kode " + kodeBuku + " tidak ditemukan.");
            }
        } catch (SQLException e) {
            System.out.println("Gagal menghapus data buku: " + e.getMessage());
        }
    }
}
