package pbo_10;

public class Buku {

    private final String kodeBuku;
    private final String judulBuku;
    private final String pengarang;
    private final int tahunTerbit;
    private final int stok;

    public Buku(String kodeBuku, String judulBuku, String pengarang, int tahunTerbit, int stok) {
        this.kodeBuku = kodeBuku;
        this.judulBuku = judulBuku;
        this.pengarang = pengarang;
        this.tahunTerbit = tahunTerbit;
        this.stok = stok;
    }

    public String getKodeBuku() {
        return kodeBuku;
    }

    public String getJudulBuku() {
        return judulBuku;
    }

    public String getPengarang() {
        return pengarang;
    }

    public int getTahunTerbit() {
        return tahunTerbit;
    }

    public int getStok() {
        return stok;
    }
}
