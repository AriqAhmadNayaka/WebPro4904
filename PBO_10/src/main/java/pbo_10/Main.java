package pbo_10;

public class Main {

    public static void main(String[] args) {
        BukuService bukuService = new BukuService();

        bukuService.createDatabase();
        bukuService.createTable();

        bukuService.insertBuku(new Buku("BK-001", "Laskar Pelangi", "Andrea Hirata", 2005, 10));
        bukuService.insertBuku(new Buku("BK-002", "Bumi Manusia", "Pramoedya Ananta Toer", 1980, 5));

        bukuService.tampilkanSemuaBuku();

        bukuService.updateStokBuku("BK-002", 20);
        bukuService.deleteBuku("BK-001");

        bukuService.tampilkanSemuaBuku();
    }
}
