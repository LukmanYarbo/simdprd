<?php

namespace App\Services;

use App\Models\Anggota;
use App\Models\ParameterGaji;
use App\Models\TunjanganUmum;
use App\Models\TunjanganPerumahan;
use App\Models\TunjanganTransportasi;
use App\Models\TunjanganKomunikasiIntensif;
use App\Models\TarifPajak;
use App\Models\Potongan;
use App\Models\TransaksiGaji;
use App\Models\PenandaTangan;
use App\Models\Pemda;
use App\Models\DsbGaji;
use Illuminate\Support\Facades\DB;

class GajiCalculatorService
{
    protected $param;       // parameter_gajis
    protected $tunUmum;     // tunjangan_umums
    protected $tunPerum;    // tunjangan_perumahans
    protected $tunTrans;    // tunjangan_transportasis
    protected $tunTki;      // tunjangan_komunikasi_intensifs
    protected $pajakParam;  // tarif_pajak
    protected $lapisPajak;  // tarif_lapis_pajak (sorted by dari ASC)
    protected $potongan;    // potongans

    public function loadParameters(): bool
    {
        $this->param = ParameterGaji::where('status', 'Y')->first();
        $this->tunUmum = TunjanganUmum::where('status', 'Y')->first();
        $this->tunPerum = TunjanganPerumahan::where('status', 'Y')->first();
        $this->tunTrans = TunjanganTransportasi::where('status', 'Y')->first();
        $this->tunTki = TunjanganKomunikasiIntensif::where('status', 'Y')->first();
        $this->pajakParam = TarifPajak::where('status', 'Y')->with('lapisPajak')->first();
        $this->potongan = Potongan::first();

        return $this->param && $this->tunUmum && $this->tunPerum
            && $this->tunTrans && $this->tunTki && $this->pajakParam
            && $this->potongan;
    }

    public function getMissingParams(): array
    {
        $missing = [];
        if (!$this->param)
            $missing[] = 'Parameter Gaji Belum ada';
        if (!$this->tunUmum)
            $missing[] = 'Tunjangan Umum Belum ada';
        if (!$this->tunPerum)
            $missing[] = 'Tunjangan Perumahan Belum ada';
        if (!$this->tunTrans)
            $missing[] = 'Tunjangan Transportasi Belum ada';
        if (!$this->tunTki)
            $missing[] = 'Tunjangan Komunikasi Intensif Belum ada';
        if (!$this->pajakParam)
            $missing[] = 'Tarif Pajak Belum ada';
        if (!$this->potongan)
            $missing[] = 'Data Potongan Belum ada';
        return $missing;
    }

    // ===========================
    // DISPATCHER
    // ===========================
    public function hitungGaji(Anggota $anggota, string $blnThn, string $metodePajak = 'ter'): array
    {
        // THR: hitung gaji lapis biasa dulu → ambil STHN & PPH setahun → teruskan ke hitungTHR
        if (str_starts_with($blnThn, 'THR-')) {
            // 1. Hitung gaji lapis biasa
            $gajiLapis = $this->hitungGajiLapis($anggota, $blnThn);
            // 2. Ambil STHN = (BRUTTO2-BJ)*12 dan PPH setahun dari field langsung
            $sthnGajiBiasa = $gajiLapis['_sthn'] ?? 0;  // neto setahun
            $pphGajiBiasa = $gajiLapis['_pph_sthn'] ?? 0;  // PPH setahun
            // 3. Hitung THR
            return $this->hitungTHR($anggota, $blnThn, $sthnGajiBiasa, $pphGajiBiasa);
        }

        if ($metodePajak === 'ter') {
            return $this->hitungGajiTER($anggota, $blnThn);
        }
        return $this->hitungGajiLapis($anggota, $blnThn);
    }

    // ===========================
    // METODE TER (PMK 168/2023)
    // ===========================
    protected function hitungGajiTER(Anggota $anggota, string $blnThn): array
    {
        $p = $this->param;
        $tu = $this->tunUmum;
        $pt = $this->tunPerum;
        $tt = $this->tunTrans;
        $tki = $this->tunTki;
        $pot = $this->potongan;

        $GAJIPOKOK = $p->gajipokok_ketua;
        $PERSENWKL = $p->persen_gapokwakil;
        $PERSENANG = $p->persen_gapokanggota;
        $PERSEN_TUNJABKETUA = $p->persen_tunjabketua;
        $PERSEN_TUNJABWKL = $p->persen_tunjabwakil;
        $PERSEN_TUNJABANG = $p->persen_tunjabanggota;
        $PERSEN_TUNKETUAALEG = $p->persen_tunketua_aleg;
        $PERSEN_TUNWKLALEG = $p->persen_tunwakil_aleg;
        $PERSEN_TUNSEKALEG = $p->persen_tunsek_aleg;
        $PERSEN_TUNANGALEG = $p->persen_tunanggota_aleg;
        $PERSEN_UANGPAKET = $p->persen_uangpaket;

        $TUN_BERAS = $tu->tunjangan_beras;
        $JLH_BERAS = $tu->jumlah_beras;
        $PERSEN_ANAK = $tu->tunjangan_anak_persen;
        $PERSEN_IS = $tu->tunjangan_istri_persen;

        $TUNPERUM_KETUA = $pt->nilai_tunjangan_ketua;
        $TUNPERUM_WAKIL = $pt->nilai_tunjangan_wakil;
        $TUNPERUM_ANG = $pt->nilai_tunjangan_anggota;

        $TUNTRANS_KETUA = $tt->nilai_tunjangan_ketua;
        $TUNTRANS_WAKIL = $tt->nilai_tunjangan_wakil;
        $TUNTRANS_ANG = $tt->nilai_tunjangan_anggota;

        $TUNTKI_VAL = $tki->nilai_tunjangan_tki;

        $TUN_BPJS = $pot->tunjangan_bpjs;
        $POT_BPJS = $pot->potongan_bpjs;
        $POT_JKK = $pot->jkk;
        $POT_JKM = $pot->jkm;
        $MAX_JKKJKM = $pot->maks_jkkjkm;

        $ID_DPRD = $anggota->id_dprd;
        $JLH_IS = $anggota->jmlh_istri ?? 0;
        $JLH_ANAK = $anggota->jmlh_anak ?? 0;
        $skRow = $anggota->statusKawin;
        $STS_KAWIN = $skRow ? $skRow->kode : 'TK';
        $KD_BPJS = $anggota->status_bpjs;
        $KD_JKK = $anggota->status_jkk;
        $KD_JKM = $anggota->status_jkm;
        $KD_TUN_PERUM = $anggota->status_tjgn_perum;
        $KD_TUN_TRANS = $anggota->status_tjgn_transport;
        $ID_KOMISI = $anggota->id_komisi;
        $ID_BANGGAR = $anggota->id_banggar;
        $ID_BANMUS = $anggota->id_banmus;
        $ID_BALEGDA = $anggota->id_balegda;
        $ID_BK = $anggota->id_bk;
        $ID_PANSUS = $anggota->id_pansus;
        $ID_PANJA = $anggota->id_panja;

        $GAPOK = 0;
        $TI = 0;
        $TA = 0;
        $TB = 0;
        $TUNJAB = 0;
        $TUNPERUM = 0;
        $TUNTRANS = 0;
        $TUNTKI = 0;
        $UANGPAKET = 0;
        $TUNBPJS = 0;
        $POTBPJS = 0;
        $JKK = 0;
        $JKM = 0;
        $JLH_PEG = 1;
        $GapokKetua = $GAJIPOKOK;

        if ($ID_DPRD == 1) {
            $GAPOK = $GapokKetua;
            $TUNJAB = $GAPOK * ($PERSEN_TUNJABKETUA / 100);
            $TUNPERUM = ($KD_TUN_PERUM == 'Y') ? $TUNPERUM_KETUA : 0;
            $TUNTRANS = ($KD_TUN_TRANS == 'Y') ? $TUNTRANS_KETUA : 0;
        } elseif ($ID_DPRD == 2) {
            $GAPOK = $GapokKetua * ($PERSENWKL / 100);
            $TUNJAB = $GAPOK * ($PERSEN_TUNJABWKL / 100);
            $TUNPERUM = ($KD_TUN_PERUM == 'Y') ? $TUNPERUM_WAKIL : 0;
            $TUNTRANS = ($KD_TUN_TRANS == 'Y') ? $TUNTRANS_WAKIL : 0;
        } else { // Anggota (ID_DPRD >= 3)
            $GAPOK = $GapokKetua * ($PERSENANG / 100);
            $TUNJAB = $GAPOK * ($PERSEN_TUNJABANG / 100);
            $TUNPERUM = ($KD_TUN_PERUM == 'Y') ? $TUNPERUM_ANG : 0;
            $TUNTRANS = ($KD_TUN_TRANS == 'Y') ? $TUNTRANS_ANG : 0;
        }

        // JKK & JKM berlaku untuk semua jabatan
        if ($KD_JKK == 'Y') {
            $JKK = ($GAPOK < $MAX_JKKJKM) ? $MAX_JKKJKM * ($POT_JKK / 100) : $GAPOK * ($POT_JKK / 100);
        }
        if ($KD_JKM == 'Y') {
            $JKM = ($GAPOK < $MAX_JKKJKM) ? $MAX_JKKJKM * ($POT_JKM / 100) : $GAPOK * ($POT_JKM / 100);
        }

        $TI = (($PERSEN_IS / 100) * $GAPOK) * $JLH_IS;
        $TA = (($PERSEN_ANAK / 100) * $GAPOK) * $JLH_ANAK;
        $TB = ($TUN_BERAS * $JLH_BERAS) * (1 + $JLH_IS + $JLH_ANAK);
        $TUNTKI = $TUNTKI_VAL;
        $UANGPAKET = $GAPOK * ($PERSEN_UANGPAKET / 100);
        $JLH_JIWA = $JLH_PEG + $JLH_IS + $JLH_ANAK;

        if ($KD_BPJS == 'Y') {
            $baseGapok = $GAPOK + $TI + $TA + $TUNJAB;
            $TUNBPJS = ($TUN_BPJS / 100) * $baseGapok;
            $POTBPJS = ($POT_BPJS / 100) * $baseGapok;
        }

        $TunJabDPRD_Ketua = $GapokKetua * ($PERSEN_TUNJABKETUA / 100);
        $TUNKOMISI = $this->hitungTunAleg($ID_KOMISI, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBANGGAR = $this->hitungTunAleg($ID_BANGGAR, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBANMUS = $this->hitungTunAleg($ID_BANMUS, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBALEGDA = $this->hitungTunAleg($ID_BALEGDA, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBK = $this->hitungTunAleg($ID_BK, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNPANSUS = $this->hitungTunAleg($ID_PANSUS, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNPANJA = $this->hitungTunAleg($ID_PANJA, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);

        // TOTALGAJI = komponen gaji utama (tidak termasuk tunjangan perumahan/transport/TKI)
        $TOTALGAJI = $GAPOK + $TI + $TA + $TB + $TUNJAB
            + $TUNKOMISI + $TUNBANGGAR + $TUNBANMUS + $TUNBALEGDA
            + $TUNBK + $TUNPANSUS + $TUNPANJA
            + $UANGPAKET + $TUNBPJS + $JKK + $JKM;

        // TUNJANGAN = perumahan + transportasi + TKI
        $TUNJANGAN = $TUNPERUM + $TUNTRANS + $TUNTKI;

        $BRUTTO = $TOTALGAJI + $TUNJANGAN;

        // Status PTKP
        $JLH_TG = $JLH_IS + $JLH_ANAK;
        $statusPtkp = ($STS_KAWIN === 'K' ? 'K' : 'TK') . '/' . min($JLH_ANAK, 3);

        // Kategori TER
        if (in_array($statusPtkp, ['TK/0', 'TK/1', 'K/0'])) {
            $kategoriTer = 'A';
        } elseif (in_array($statusPtkp, ['TK/2', 'TK/3', 'K/1', 'K/2'])) {
            $kategoriTer = 'B';
        } elseif ($statusPtkp === 'K/3') {
            $kategoriTer = 'C';
        } else {
            $kategoriTer = 'A';
        }

        // Tarif TER
        $persenTer = 0;
        if ($kategoriTer === 'A') {
            $persenTer = $this->hitungNTAXPerKategoriA($BRUTTO);
        } elseif ($kategoriTer === 'B') {
            $persenTer = $this->hitungNTAXPerKategoriB($BRUTTO);
        } elseif ($kategoriTer === 'C') {
            $persenTer = $this->hitungNTAXPerKategoriC($BRUTTO);
        }

        $PPhTER = round($BRUTTO * ($persenTer / 100));
        $rasioTunjangan = ($TOTALGAJI > 0) ? (($TUNJANGAN / $TOTALGAJI) / 100) : 0;
        $PPH21_Gaji = round($PPhTER * $rasioTunjangan);
        $PPH21_Tunjangan = $PPhTER - $PPH21_Gaji;
        $TOTAL_BERSIH = $BRUTTO - $PPH21_Tunjangan;

        $DETAIL_PAJAK = [
            'metode' => 'ter',
            'Total Pendapatan' => $BRUTTO,
            'Gaji' => $TOTALGAJI,
            'Tunjangan' => $TUNJANGAN,
            'status_kawin' => $STS_KAWIN,
            'status_ptkp' => $statusPtkp,
            'kategori_ter' => 'Kategori ' . $kategoriTer,
            'persen_ter' => $persenTer,
            'pph_sebulan' => $PPhTER,
            'pph_gaji' => $PPH21_Gaji,
            'pph_tunjangan' => $PPH21_Tunjangan,
        ];

        return [
            'bln_thn' => $blnThn,
            'id_anggota' => $anggota->id,
            'jumlah_is' => $JLH_IS,
            'jumlah_anak' => $JLH_ANAK,
            'status_kawin' => $STS_KAWIN,
            'jumlah_pegawai' => $JLH_PEG,
            'jumlah_jiwa' => $JLH_JIWA,
            'gaji_pokok' => (int) round($GAPOK),
            'tunjangan_anak' => (int) round($TA),
            'tunjangan_istri' => (int) round($TI),
            'tunjangan_beras' => (int) round($TB),
            'tunjangan_paket' => (int) round($UANGPAKET),
            'tunjangan_jabatan' => (int) round($TUNJAB),
            'tunjangan_komisi' => (int) round($TUNKOMISI),
            'tunjangan_banggar' => (int) round($TUNBANGGAR),
            'tunjangan_banmus' => (int) round($TUNBANMUS),
            'tunjangan_balegda' => (int) round($TUNBALEGDA),
            'tunjangan_bk' => (int) round($TUNBK),
            'tunjangan_pansus' => (int) round($TUNPANSUS),
            'tunjangan_panja' => (int) round($TUNPANJA),
            'pembulatan' => 0,
            'brutto1' => (int) round($BRUTTO),
            'brutto2' => 0,
            'tunjangan_pph21' => (int) round($PPhTER),
            'tunjangan_bpjs' => (int) round($TUNBPJS),
            'tunjangan_jkk' => (int) round($JKK),
            'tunjangan_jkm' => (int) round($JKM),
            'Kategori_TER' => $kategoriTer,
            'Nilai_TER' => $persenTer,
            'PPH21_Gaji' => (int) round($PPH21_Gaji),
            'PPh21_Tunjangan' => (int) round($PPH21_Tunjangan),
            'potongan_pph21' => (int) round($PPhTER),
            'potongan_bpjs' => (int) round($POTBPJS),
            'potongan_bpjs2' => 0,
            'potongan_jkk' => (int) round($JKK),
            'potongan_jkm' => (int) round($JKM),
            'nilai_netto' => 0,
            'tunjangan_perumahan' => (int) round($TUNPERUM),
            'tunjangan_transportasi' => (int) round($TUNTRANS),
            'tunjangan_tki' => (int) round($TUNTKI),
            'tunjangan_reses' => 0,
            'potonganpph_perumahan' => 0,
            'potonganpph_transportasi' => 0,
            'potonganpph_tki' => 0,
            'potonganpph_reses' => 0,
            'nilai_gajitunjangan' => (int) round($BRUTTO),
            'total_potongan1' => 0,
            'total_potongan2' => 0,
            'jumlah_bersih' => (int) round($TOTAL_BERSIH),
            'detail_pajak' => $DETAIL_PAJAK,
        ];
    }

    // ===========================
    // METODE LAPIS PAJAK LAMA (Pasal 17)
    // ===========================
    protected function hitungGajiLapis(Anggota $anggota, string $blnThn): array
    {
        $p = $this->param;
        $tu = $this->tunUmum;
        $pt = $this->tunPerum;
        $tt = $this->tunTrans;
        $tki = $this->tunTki;
        $px = $this->pajakParam;
        $pot = $this->potongan;

        $GAJIPOKOK = $p->gajipokok_ketua;
        $PERSENWKL = $p->persen_gapokwakil;
        $PERSENANG = $p->persen_gapokanggota;
        $PERSEN_TUNJABKETUA = $p->persen_tunjabketua;
        $PERSEN_TUNJABWKL = $p->persen_tunjabwakil;
        $PERSEN_TUNJABANG = $p->persen_tunjabanggota;
        $PERSEN_TUNKETUAALEG = $p->persen_tunketua_aleg;
        $PERSEN_TUNWKLALEG = $p->persen_tunwakil_aleg;
        $PERSEN_TUNSEKALEG = $p->persen_tunsek_aleg;
        $PERSEN_TUNANGALEG = $p->persen_tunanggota_aleg;
        $PERSEN_UANGPAKET = $p->persen_uangpaket;

        $TUN_BERAS = $tu->tunjangan_beras;
        $JLH_BERAS = $tu->jumlah_beras;
        $PERSEN_ANAK = $tu->tunjangan_anak_persen;
        $PERSEN_IS = $tu->tunjangan_istri_persen;

        $TUNPERUM_KETUA = $pt->nilai_tunjangan_ketua;
        $TUNPERUM_WAKIL = $pt->nilai_tunjangan_wakil;
        $TUNPERUM_ANG = $pt->nilai_tunjangan_anggota;

        $TUNTRANS_KETUA = $tt->nilai_tunjangan_ketua;
        $TUNTRANS_WAKIL = $tt->nilai_tunjangan_wakil;
        $TUNTRANS_ANG = $tt->nilai_tunjangan_anggota;

        $TUNTKI_VAL = $tki->nilai_tunjangan_tki;

        $PTKP = $px->ptkp;
        $PTKP_PLUS = $px->tambahan_ptkp_tanggungan;
        $BIAYA_JAB = $px->persen_biaya_jabatan;
        $MAX_BIAYA_JAB = $px->max_biaya_jabatan;
        $lapisPajak = $px->lapisPajak->sortBy('dari');

        $TUN_BPJS = $pot->tunjangan_bpjs;
        $POT_BPJS = $pot->potongan_bpjs;
        $MAX_BPJS = $pot->maksimal_potongan_bpjs;
        $POT_JKK = $pot->jkk;
        $POT_JKM = $pot->jkm;
        $MAX_JKKJKM = $pot->maks_jkkjkm;
        $POT_PPH15 = $pot->pot_pph;

        $ID_DPRD = $anggota->id_dprd;
        $JLH_IS = $anggota->jmlh_istri ?? 0;
        $JLH_ANAK = $anggota->jmlh_anak ?? 0;
        $skRow = $anggota->statusKawin;
        $STS_KAWIN = $skRow ? $skRow->kode : 'TK';
        $KD_BPJS = $anggota->status_bpjs;
        $KD_JKK = $anggota->status_jkk;
        $KD_JKM = $anggota->status_jkm;
        $KD_TUN_PERUM = $anggota->status_tjgn_perum;
        $KD_TUN_TRANS = $anggota->status_tjgn_transport;
        $ID_KOMISI = $anggota->id_komisi;
        $ID_BANGGAR = $anggota->id_banggar;
        $ID_BANMUS = $anggota->id_banmus;
        $ID_BALEGDA = $anggota->id_balegda;
        $ID_BK = $anggota->id_bk;
        $ID_PANSUS = $anggota->id_pansus;
        $ID_PANJA = $anggota->id_panja;

        $GAPOK = 0;
        $TI = 0;
        $TA = 0;
        $TB = 0;
        $TUNJAB = 0;
        $TUNPERUM = 0;
        $TUNTRANS = 0;
        $TUNTKI = 0;
        $UANGPAKET = 0;
        $JLH_PEG = 1;
        $JLH_JIWA = 0;
        $TUNBPJS = 0;
        $POTBPJS = 0;
        $JKK = 0;
        $JKM = 0;
        $GapokKetua = $GAJIPOKOK;
        $TUNPERUM_VAL = 0;
        $TUNTRANS_VAL = 0;
        $PERSEN_TUNJAB = 0;
        $PERSEN_GAPOK = 100;

        if ($ID_DPRD == 1) {
            $PERSEN_TUNJAB = $PERSEN_TUNJABKETUA;
            $TUNPERUM_VAL = $TUNPERUM_KETUA;
            $TUNTRANS_VAL = $TUNTRANS_KETUA;
        } elseif ($ID_DPRD == 2) {
            $PERSEN_GAPOK = $PERSENWKL;
            $PERSEN_TUNJAB = $PERSEN_TUNJABWKL;
            $TUNPERUM_VAL = $TUNPERUM_WAKIL;
            $TUNTRANS_VAL = $TUNTRANS_WAKIL;
        } else {
            $PERSEN_GAPOK = $PERSENANG;
            $PERSEN_TUNJAB = $PERSEN_TUNJABANG;
            $TUNPERUM_VAL = $TUNPERUM_ANG;
            $TUNTRANS_VAL = $TUNTRANS_ANG;
        }

        $GAPOK = $GapokKetua * ($PERSEN_GAPOK / 100);
        $TI = (($PERSEN_IS / 100) * $GAPOK) * $JLH_IS;
        $TA = (($PERSEN_ANAK / 100) * $GAPOK) * $JLH_ANAK;
        $TB = ($TUN_BERAS * $JLH_BERAS) * (1 + $JLH_IS + $JLH_ANAK);
        $TUNJAB = $GAPOK * ($PERSEN_TUNJAB / 100);
        $TUNPERUM = ($KD_TUN_PERUM == 'Y') ? $TUNPERUM_VAL : 0;
        $TUNTRANS = ($KD_TUN_TRANS == 'Y') ? $TUNTRANS_VAL : 0;
        $TUNTKI = $TUNTKI_VAL;
        $UANGPAKET = $GAPOK * ($PERSEN_UANGPAKET / 100);
        $JLH_JIWA = 1 + $JLH_IS + $JLH_ANAK;

        if ($KD_BPJS == 'Y') {
            $baseGapok = $GAPOK + $TI + $TA + $TUNJAB;
            $TUNBPJS = ($TUN_BPJS / 100) * $baseGapok;
            $POTBPJS = ($POT_BPJS / 100) * $baseGapok;
        }
        if ($KD_JKK == 'Y') {
            $JKK = ($GAPOK < $MAX_JKKJKM) ? $MAX_JKKJKM * ($POT_JKK / 100) : $GAPOK * ($POT_JKK / 100);
        }
        if ($KD_JKM == 'Y') {
            $JKM = ($GAPOK < $MAX_JKKJKM) ? $MAX_JKKJKM * ($POT_JKM / 100) : $GAPOK * ($POT_JKM / 100);
        }

        $TunJabDPRD_Ketua = $GapokKetua * ($PERSEN_TUNJABKETUA / 100);
        $TUNKOMISI = $this->hitungTunAleg($ID_KOMISI, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBANGGAR = $this->hitungTunAleg($ID_BANGGAR, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBANMUS = $this->hitungTunAleg($ID_BANMUS, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBALEGDA = $this->hitungTunAleg($ID_BALEGDA, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBK = $this->hitungTunAleg($ID_BK, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNPANSUS = $this->hitungTunAleg($ID_PANSUS, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNPANJA = $this->hitungTunAleg($ID_PANJA, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);

        $POTPPH_TUNPERUM = ($POT_PPH15 / 100) * $TUNPERUM;
        $POTPPH_TUNTRANS = ($POT_PPH15 / 100) * $TUNTRANS;
        $POTPPH_TUNTKI = ($POT_PPH15 / 100) * $TUNTKI;
        $TOT_POT_PPH15 = $POTPPH_TUNPERUM + $POTPPH_TUNTRANS + $POTPPH_TUNTKI;

        $BRUTTO = $GAPOK + $TI + $TA + $TB + $TUNJAB
            + $TUNKOMISI + $TUNBANGGAR + $TUNBANMUS + $TUNBALEGDA
            + $TUNBK + $TUNPANSUS + $TUNPANJA
            + $UANGPAKET + $TUNBPJS + $JKK + $JKM;

        $POT = $JKK + $JKM + $TUNBPJS + $POTBPJS;
        $JLH1 = $BRUTTO - $POT;
        $BRUTTO1 = ceil($JLH1 / 100) * 100;
        $PEMBULATAN = $BRUTTO1 - $JLH1;
        $BRUTTO2 = $BRUTTO + $PEMBULATAN;

        $JLH_TG = $JLH_IS + $JLH_ANAK;
        $statusPtkp = ($STS_KAWIN === 'K' ? 'K' : 'TK') . '/' . min($JLH_ANAK, 3);

        $BJ_hitung = ($BIAYA_JAB / 100) * $BRUTTO2;
        $BJ = min($BJ_hitung, $MAX_BIAYA_JAB);
        $KRG = $BRUTTO2 - $BJ;
        $STHN = $KRG * 12;
        $PJK1 = $PTKP + ($JLH_TG * $PTKP_PLUS);
        $PKP_KOTOR = $STHN - $PJK1;
        $PKP = floor($PKP_KOTOR / 1000) * 1000;
        $PKP2 = $this->hitungPKP2($PKP, $lapisPajak);
        $PPH_STHN = floor($PKP2);
        $TOT_PAJAK = max(0, $PPH_STHN);
        $PPH = $TOT_PAJAK / 12;
        $POT_PPH = $TOT_PAJAK / 12;




        $DETAIL_PAJAK = [
            'metode' => 'lapis',
            'bruto_sebulan_lapis' => $BRUTTO2,
            'persen_biaya_jab' => $BIAYA_JAB,
            'biaya_jabatan' => $BJ_hitung,
            'max_biaya_jab' => $MAX_BIAYA_JAB,
            'neto_sebulan' => $KRG,
            'neto_setahun' => $STHN,
            'status_kawin' => $STS_KAWIN,
            'status_ptkp' => $statusPtkp,
            'nilai_ptkp' => $PJK1,
            'pkp_kotor' => $PKP_KOTOR,
            'pkp_pembulatan' => $PKP,
            'pph_setahun' => $TOT_PAJAK,
            'pph_sebulan' => $PPH,
        ];

        $TOT_KOTOR = $BRUTTO + $PPH + $PEMBULATAN;
        $TOTAL = $TOT_KOTOR - ($POT_PPH + $POT);
        $TOT_POT2 = $POT + $POT_PPH;
        $TOT_GAJI_TUN = $TOT_KOTOR + $TUNPERUM + $TUNTRANS + $TUNTKI;
        $TOT_POT = $TOT_POT_PPH15 + $TOT_POT2;
        $TOTAL_BERSIH = $TOT_GAJI_TUN - $TOT_POT;
        $DETAIL_PAJAK['bruto_sebulan_lapis'] = $BRUTTO2;

        return [
            'bln_thn' => $blnThn,
            'id_anggota' => $anggota->id,
            'jumlah_is' => $JLH_IS,
            'jumlah_anak' => $JLH_ANAK,
            'status_kawin' => $STS_KAWIN,
            'jumlah_pegawai' => $JLH_PEG,
            'jumlah_jiwa' => $JLH_JIWA,
            'gaji_pokok' => (int) round($GAPOK),
            'tunjangan_anak' => (int) round($TA),
            'tunjangan_istri' => (int) round($TI),
            'tunjangan_beras' => (int) round($TB),
            'tunjangan_paket' => (int) round($UANGPAKET),
            'tunjangan_jabatan' => (int) round($TUNJAB),
            'tunjangan_komisi' => (int) round($TUNKOMISI),
            'tunjangan_banggar' => (int) round($TUNBANGGAR),
            'tunjangan_banmus' => (int) round($TUNBANMUS),
            'tunjangan_balegda' => (int) round($TUNBALEGDA),
            'tunjangan_bk' => (int) round($TUNBK),
            'tunjangan_pansus' => (int) round($TUNPANSUS),
            'tunjangan_panja' => (int) round($TUNPANJA),
            'pembulatan' => (int) round($PEMBULATAN),
            'brutto1' => (int) round($BRUTTO1),
            'brutto2' => (int) round($BRUTTO2),
            'tunjangan_pph21' => (int) round($PPH),
            'tunjangan_bpjs' => (int) round($TUNBPJS),
            'tunjangan_jkk' => (int) round($JKK),
            'tunjangan_jkm' => (int) round($JKM),
            'Kategori_TER' => null,
            'Nilai_TER' => null,
            'PPH21_Gaji' => 0,
            'PPh21_Tunjangan' => 0,
            'potongan_pph21' => (int) round($POT_PPH),
            'potongan_bpjs' => (int) round($POTBPJS),
            'potongan_bpjs2' => 0,
            'potongan_jkk' => (int) round($JKK),
            'potongan_jkm' => (int) round($JKM),
            'nilai_netto' => (int) round($TOTAL),
            'tunjangan_perumahan' => (int) round($TUNPERUM),
            'tunjangan_transportasi' => (int) round($TUNTRANS),
            'tunjangan_tki' => (int) round($TUNTKI),
            'tunjangan_reses' => 0,
            'potonganpph_perumahan' => (int) round($POTPPH_TUNPERUM),
            'potonganpph_transportasi' => (int) round($POTPPH_TUNTRANS),
            'potonganpph_tki' => (int) round($POTPPH_TUNTKI),
            'potonganpph_reses' => 0,
            'nilai_gajitunjangan' => (int) round($TOT_GAJI_TUN),
            'total_potongan1' => (int) round($TOT_POT),
            'total_potongan2' => (int) round($TOT_POT2),
            'jumlah_bersih' => (int) round($TOTAL_BERSIH),
            'detail_pajak' => $DETAIL_PAJAK,
            // Field internal untuk perhitungan THR (tidak disimpan ke DB)
            '_sthn' => $BRUTTO2 * 12,      // neto setahun = (BRUTTO2 - BJ) * 12
            '_pph_sthn' => $PPH_STHN,  // PPH setahun = floor(PKP2)
        ];
    }

    // ===========================
    // METODE THR (Tunjangan Hari Raya)
    // ===========================
    protected function hitungTHR(Anggota $anggota, string $blnThn, float $sthnGajiBiasa = 0, float $pphGajiBiasa = 0): array
    {
        $p = $this->param;
        $tu = $this->tunUmum;
        $px = $this->pajakParam;

        $GAJIPOKOK = $p->gajipokok_ketua;
        $PERSENWKL = $p->persen_gapokwakil;
        $PERSENANG = $p->persen_gapokanggota;
        $PERSEN_TUNJABKETUA = $p->persen_tunjabketua;
        $PERSEN_TUNJABWKL = $p->persen_tunjabwakil;
        $PERSEN_TUNJABANG = $p->persen_tunjabanggota;

        $PERSEN_ANAK = $tu->tunjangan_anak_persen;
        $PERSEN_IS = $tu->tunjangan_istri_persen;

        $PTKP = $px->ptkp;
        $PTKP_PLUS = $px->tambahan_ptkp_tanggungan;
        $BIAYA_JAB = $px->persen_biaya_jabatan;
        $MAX_BIAYA_JAB = $px->max_biaya_jabatan;
        $lapisPajak = $px->lapisPajak->sortBy('dari');

        $ID_DPRD = $anggota->id_dprd;
        $JLH_IS = $anggota->jmlh_istri ?? 0;
        $JLH_ANAK = $anggota->jmlh_anak ?? 0;
        $skRow = $anggota->statusKawin;
        $STS_KAWIN = $skRow ? $skRow->kode : 'TK';

        $JLH_PEG = 1;
        $GapokKetua = $GAJIPOKOK;

        // Tentukan persen berdasarkan jabatan
        $PERSEN_GAPOK = 100;
        $PERSEN_TUNJAB = $PERSEN_TUNJABKETUA;

        if ($ID_DPRD == 1) {
            $PERSEN_TUNJAB = $PERSEN_TUNJABKETUA;
        } elseif ($ID_DPRD == 2) {
            $PERSEN_GAPOK = $PERSENWKL;
            $PERSEN_TUNJAB = $PERSEN_TUNJABWKL;
        } else {
            $PERSEN_GAPOK = $PERSENANG;
            $PERSEN_TUNJAB = $PERSEN_TUNJABANG;
        }

        // Komponen THR: GAPOK, TI, TA, TUNJAB
        $GAPOK_THR = $GapokKetua * ($PERSEN_GAPOK / 100);
        $TI_THR = (($PERSEN_IS / 100) * $GAPOK_THR) * $JLH_IS;
        $TA_THR = (($PERSEN_ANAK / 100) * $GAPOK_THR) * $JLH_ANAK;
        $TUNJAB_THR = $GAPOK_THR * ($PERSEN_TUNJAB / 100);

        $JLH_JIWA = 1 + $JLH_IS + $JLH_ANAK;
        $JLH_TG = $JLH_IS + $JLH_ANAK;
        $statusPtkp = ($STS_KAWIN === 'K' ? 'K' : 'TK') . '/' . min($JLH_ANAK, 3);

        // BRUTTO THR (sebelum pembulatan)
        $BRUTTO_THR = $GAPOK_THR + $TI_THR + $TA_THR + $TUNJAB_THR;

        // Pembulatan ke atas kelipatan 100
        $BRUTTO1_THR = ceil($BRUTTO_THR / 100) * 100;
        $PEMBULATAN_THR = $BRUTTO1_THR - $BRUTTO_THR;

        // STHN dan PPH setahun gaji biasa diterima langsung dari dispatcher
        // (sudah dihitung via hitungGajiLapis yang mencakup semua komponen)
        $STHN_GAJI_BIASA = $sthnGajiBiasa;
        $PPH_GAJI_BLNBIASA = $pphGajiBiasa;

        $BRUTTO2_THR = $BRUTTO_THR + $PEMBULATAN_THR + $STHN_GAJI_BIASA;

        // Biaya Jabatan THR
        $BJ_hitung = ($BIAYA_JAB / 100) * $BRUTTO2_THR;
        $BJ_THR = min($BJ_hitung, $MAX_BIAYA_JAB * 12); // max biaya jabatan setahun untuk THR
        $KRG_THR = $BRUTTO2_THR - $BJ_THR;

        // PTKP
        $PJK1_THR = $PTKP + ($JLH_TG * $PTKP_PLUS);

        // PKP THR
        $PKP_KOTOR_THR = $KRG_THR - $PJK1_THR;
        $PKP_THR = floor($PKP_KOTOR_THR / 1000) * 1000;
        $PKP2_THR = $this->hitungPKP2($PKP_THR, $lapisPajak);
        $PPH_STHN_THR = floor($PKP2_THR);
        $TOT_PAJAK_THR = $PPH_STHN_THR;

        // PPH THR = pajak setahun THR dikurangi PPH gaji bulan biasa (selalu positif)
        $PPH_THR = abs($PPH_STHN_THR - $PPH_GAJI_BLNBIASA);
        $POT_PPH_THR = $PPH_THR;

        // Total
        $TOT_KOTOR_THR = $PEMBULATAN_THR + $BRUTTO_THR + $PPH_THR;
        $TOTAL_THR = $TOT_KOTOR_THR - $POT_PPH_THR;
        $TOT_GAJI_THR = $TOT_KOTOR_THR;
        $TOT_POT_THR = $PPH_THR;
        $TOTAL_BERSIH_THR = $TOT_GAJI_THR - $TOT_POT_THR;

        return [
            'bln_thn' => $blnThn,
            'id_anggota' => $anggota->id,
            'jumlah_is' => $JLH_IS,
            'jumlah_anak' => $JLH_ANAK,
            'status_kawin' => $STS_KAWIN,
            'jumlah_pegawai' => $JLH_PEG,
            'jumlah_jiwa' => $JLH_JIWA,
            'gaji_pokok' => (int) round($GAPOK_THR),
            'tunjangan_anak' => (int) round($TA_THR),
            'tunjangan_istri' => (int) round($TI_THR),
            'tunjangan_beras' => 0,
            'tunjangan_paket' => 0,
            'tunjangan_jabatan' => (int) round($TUNJAB_THR),
            'tunjangan_komisi' => 0,
            'tunjangan_banggar' => 0,
            'tunjangan_banmus' => 0,
            'tunjangan_balegda' => 0,
            'tunjangan_bk' => 0,
            'tunjangan_pansus' => 0,
            'tunjangan_panja' => 0,
            'pembulatan' => (int) round($PEMBULATAN_THR),
            'brutto1' => (int) round($BRUTTO1_THR),
            'brutto2' => (int) round($TOT_KOTOR_THR),
            'tunjangan_pph21' => (int) round($PPH_THR),
            'tunjangan_bpjs' => 0,
            'tunjangan_jkk' => 0,
            'tunjangan_jkm' => 0,
            'Kategori_TER' => null,
            'Nilai_TER' => null,
            'PPH21_Gaji' => 0,
            'PPh21_Tunjangan' => 0,
            'potongan_pph21' => (int) round($PPH_THR),
            'potongan_bpjs' => 0,
            'potongan_bpjs2' => 0,
            'potongan_jkk' => 0,
            'potongan_jkm' => 0,
            'nilai_netto' => (int) round($TOTAL_THR),
            'tunjangan_perumahan' => 0,
            'tunjangan_transportasi' => 0,
            'tunjangan_tki' => 0,
            'tunjangan_reses' => 0,
            'potonganpph_perumahan' => 0,
            'potonganpph_transportasi' => 0,
            'potonganpph_tki' => 0,
            'potonganpph_reses' => 0,
            'nilai_gajitunjangan' => (int) round($TOT_GAJI_THR),
            'total_potongan1' => (int) round($TOT_POT_THR),
            'total_potongan2' => (int) round($TOT_POT_THR),
            'jumlah_bersih' => (int) round($TOTAL_BERSIH_THR),
            'detail_pajak' => [
                'metode' => 'thr',
                'bruto_thr' => $BRUTTO_THR,
                'pembulatan' => $PEMBULATAN_THR,
                'bruto1_thr' => $BRUTTO1_THR,
                'sthn_gaji_biasa' => $STHN_GAJI_BIASA,
                'bruto2_thr' => $BRUTTO2_THR,
                'biaya_jabatan_thr' => $BJ_THR,
                'neto_thr' => $KRG_THR,
                'status_ptkp' => $statusPtkp,
                'nilai_ptkp' => $PJK1_THR,
                'pkp_kotor_thr' => $PKP_KOTOR_THR,
                'pkp_thr' => $PKP_THR,
                'pph_sthn_thr' => $TOT_PAJAK_THR,
                'pph_gaji_biasa' => $PPH_GAJI_BLNBIASA,
                'pph_thr' => $PPH_THR,
            ],
        ];
    }

    // ===========================
    // HELPERS
    // ===========================
    protected function hitungTunAleg(?int $jabatanId, float $tunJabKetua, float $pKetua, float $pWakil, float $pSek, float $pAnggota): float
    {
        if (!$jabatanId)
            return 0;

        return match ($jabatanId) {
            1 => $tunJabKetua * ($pKetua / 100),
            2 => $tunJabKetua * ($pWakil / 100),
            3 => $tunJabKetua * ($pSek / 100),
            4 => $tunJabKetua * ($pAnggota / 100),
            default => 0,
        };
    }

    protected function hitungPKP2(float $PKP, $lapisPajak): float
    {
        if ($PKP <= 0)
            return 0;

        $lapisArr = $lapisPajak->values()->all();
        $jumlahLapis = count($lapisArr);

        if ($jumlahLapis === 0)
            return 0;

        $PKP2 = 0;
        $sisa = $PKP;

        foreach ($lapisArr as $i => $lapis) {
            $dari = $lapis->dari;
            $sampai = $lapis->sampai; // null = tidak terbatas
            $persen = $lapis->persen;

            if ($sisa <= 0)
                break;

            if ($sampai === null) {
                $kena = $sisa;
            } else {
                $batas = $sampai - $dari;
                $kena = min($sisa, $batas);
            }

            $PKP2 += $kena * ($persen / 100);
            $sisa -= $kena;
        }

        return $PKP2;
    }

    protected function hitungNTAXPerKategoriA(float $BRUTTO): float
    {
        $thresholds = [11400000000, 910000000, 695000000, 550000000, 454000000, 337000000, 206000000, 157000000, 125000000, 103000000, 89000000, 77500000, 68600000, 62200000, 56300000, 51400000, 47800000, 43850000, 39100000, 35400000, 32400000, 30050000, 28000000, 26450000, 24150000, 19750000, 16950000, 15100000, 13750000, 12500000, 11600000, 11050000, 10700000, 10350000, 10050000, 9650000, 8550000, 7500000, 6750000, 6300000, 5950000, 5650000, 5400000];
        $rates = [34, 33, 32, 31, 30, 29, 28, 27, 26, 25, 24, 23, 22, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3.5, 3, 2.5, 2.25, 2, 1.75, 1.5, 1.25, 1, 0.75, 0.5, 0.25, 0];

        foreach ($thresholds as $i => $threshold) {
            if ($BRUTTO > $threshold) {
                return (float) $rates[$i];
            }
        }
        return 0;
    }

    protected function hitungNTAXPerKategoriB(float $BRUTTO): float
    {
        $thresholds = [1405000000, 957000000, 704000000, 555000000, 459000000, 374000000, 211000000, 163000000, 129000000, 109000000, 93000000, 80000000, 71000000, 64000000, 58500000, 53800000, 49500000, 45800000, 41100000, 37100000, 33950000, 31450000, 29350000, 27700000, 26000000, 21850000, 18450000, 16400000, 14950000, 13600000, 12600000, 11600000, 11250000, 10750000, 9200000, 7300000, 6850000, 6500000, 6200000];
        $rates = [34, 33, 32, 31, 30, 29, 28, 27, 26, 25, 24, 23, 22, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2.5, 2, 1.5, 1.25, 1, 0.75, 0.5, 0.25, 0];

        foreach ($thresholds as $i => $threshold) {
            if ($BRUTTO > $threshold) {
                return (float) $rates[$i];
            }
        }
        return 0;
    }

    protected function hitungNTAXPerKategoriC(float $BRUTTO): float
    {
        $thresholds = [1419000000, 965000000, 709000000, 561000000, 463000000, 390000000, 221000000, 169000000, 134000000, 110000000, 95600000, 83200000, 74500000, 66700000, 60400000, 55800000, 51200000, 47400000, 43000000, 38900000, 35400000, 32600000, 30100000, 28100000, 26600000, 22700000, 19500000, 17050000, 15550000, 14150000, 12950000, 12050000, 11200000, 10950000, 9800000, 8850000, 7800000, 7350000, 6950000, 6600000];
        $rates = [34, 33, 32, 31, 30, 29, 28, 27, 26, 25, 24, 23, 22, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1.75, 1.5, 1.25, 1, 0.75, 0.5, 0.25, 0];

        foreach ($thresholds as $i => $threshold) {
            if ($BRUTTO > $threshold) {
                return (float) $rates[$i];
            }
        }
        return 0;
    }

    public function getDsbGajiData(string $blnThn): array
    {
        $detailsByPosition = TransaksiGaji::where('bln_thn', $blnThn)
            ->join('anggota', 'transaksi_gaji.id_anggota', '=', 'anggota.id')
            ->where('anggota.id_status_keanggotaan', 1) // Only Active members
            ->select(
                DB::raw('CASE WHEN anggota.id_dprd = 1 THEN "KETUA" 
                             WHEN anggota.id_dprd = 2 THEN "WAKIL KETUA" 
                             ELSE "ANGGOTA" END as jabatan_group'),
                'anggota.id_dprd',
                DB::raw('COUNT(*) as pegawai'),
                DB::raw('SUM(transaksi_gaji.jumlah_is) as istri'),
                DB::raw('SUM(transaksi_gaji.jumlah_anak) as anak'),
                DB::raw('SUM(transaksi_gaji.jumlah_jiwa) as jiwa')
            )
            ->groupBy('anggota.id_dprd', 'jabatan_group')
            ->orderBy('anggota.id_dprd', 'asc')
            ->get();

        $bendahara = PenandaTangan::where(function ($q) {
            $q->where('jenis_dokumen', 'like', '%pengesahan%gaji%')
                ->orWhere('jenis_dokumen', 'like', '%pengesahan_gaji%')
                ->orWhere('jenis_dokumen', 'like', '%pegesahan_gaji%') // potential typo from user
                ->orWhere('jenis_dokumen', 'like', '%Pengajuan Gaji%');
        })
            ->with(['pegawaiAsn.jabatanAsn', 'pegawaiAsn.pangkatGolongan'])
            ->get()
            ->filter(function ($item) {
                $jabatan = strtolower($item->pegawaiAsn->jabatanAsn->nama_jabatan ?? '');
                $ket = strtolower($item->pegawaiAsn->ket_jabatan ?? '');
                return str_contains($jabatan, 'bendahara') || str_contains($ket, 'bendahara');
            })->first();

        $sekretaris = PenandaTangan::where(function ($q) {
            $q->where('jenis_dokumen', 'like', '%pengesahan%gaji%')
                ->orWhere('jenis_dokumen', 'like', '%pengesahan_gaji%')
                ->orWhere('jenis_dokumen', 'like', '%pegesahan_gaji%')
                ->orWhere('jenis_dokumen', 'like', '%Pengajuan Gaji%');
        })
            ->with(['pegawaiAsn.jabatanAsn', 'pegawaiAsn.pangkatGolongan'])
            ->get()
            ->filter(function ($item) {
                $jabatan = strtolower($item->pegawaiAsn->jabatanAsn->nama_jabatan ?? '');
                $ket = strtolower($item->pegawaiAsn->ket_jabatan ?? '');
                return str_contains($jabatan, 'sekretaris dprd') || str_contains($ket, 'sekretaris dprd');
            })->first();

        $pemda = Pemda::first();

        return [
            'detailsByPosition' => $detailsByPosition,
            'bendahara' => $bendahara,
            'sekretaris' => $sekretaris,
            'pemda' => $pemda
        ];
    }

    public function deleteDsbGaji(string $blnThn): void
    {
        DsbGaji::where('bln_thn', $blnThn)->delete();
    }

    public function saveDsbGaji(string $blnThn, $tanggalProses = null): void
    {
        $data = $this->getDsbGajiData($blnThn);
        $details = $data['detailsByPosition'];
        $sekretaris = $data['sekretaris'];
        $bendahara = $data['bendahara'];

        $ketua = $details->where('id_dprd', 1)->first();
        $wakil = $details->where('id_dprd', 2)->first();
        $anggotaList = $details->where('id_dprd', '>', 2);

        // Accumulations as requested
        $jumlah_is_ketua = $ketua->istri ?? 0;
        $jumlah_is_wakil = $wakil->istri ?? 0;
        $jumlah_is_anggota = $anggotaList->sum('istri') ?? 0;
        $jumlah_is = $jumlah_is_ketua + $jumlah_is_wakil + $jumlah_is_anggota;

        $jumlah_anak_ketua = $ketua->anak ?? 0;
        $jumlah_anak_wakil = $wakil->anak ?? 0;
        $jumlah_anak_anggota = $anggotaList->sum('anak') ?? 0;
        $jumlah_anak = $jumlah_anak_ketua + $jumlah_anak_wakil + $jumlah_anak_anggota;

        $jumlah_ketua = $ketua->pegawai ?? 0;
        $jumlah_wakil = $wakil->pegawai ?? 0;
        $jumlah_anggota_count = $anggotaList->sum('pegawai') ?? 0;
        $jumlah_pegawai = $jumlah_ketua + $jumlah_wakil + $jumlah_anggota_count;

        $jumlah_jiwa = $jumlah_pegawai + $jumlah_is + $jumlah_anak;

        DsbGaji::updateOrCreate(
            ['bln_thn' => $blnThn],
            [
                'jumlah_jiwa' => $jumlah_jiwa,
                'jumlah_pegawai' => $jumlah_pegawai,
                'jumlah_is' => $jumlah_is,
                'jumlah_anak' => $jumlah_anak,
                'jumlah_ketua' => $jumlah_ketua,
                'jumlah_wakil' => $jumlah_wakil,
                'jumlah_anggota' => $jumlah_anggota_count,
                'jumlah_is_ketua' => $jumlah_is_ketua,
                'jumlah_anak_ketua' => $jumlah_anak_ketua,
                'jumlah_is_wakil' => $jumlah_is_wakil,
                'jumlah_anak_wakil' => $jumlah_anak_wakil,
                'jumlah_is_anggota' => $jumlah_is_anggota,
                'jumlah_anak_anggota' => $jumlah_anak_anggota,
                'nama_pa' => $sekretaris?->pegawaiAsn?->nama ?? '',
                'nip_pa' => $sekretaris?->pegawaiAsn?->nip ?? '',
                'golongan_pa' => isset($sekretaris?->pegawaiAsn?->pangkatGolongan)
                    ? ($sekretaris->pegawaiAsn->pangkatGolongan->pangkat . ', ' . $sekretaris->pegawaiAsn->pangkatGolongan->golongan)
                    : '',
                'jabatan_pa' => $sekretaris?->pegawaiAsn?->jabatanAsn?->nama_jabatan ?? ($sekretaris?->pegawaiAsn?->ket_jabatan ?? ''),
                'nama_bendahara' => $bendahara?->pegawaiAsn?->nama ?? '',
                'nip_bendahara' => $bendahara?->pegawaiAsn?->nip ?? '',
                'golongan_bendahara' => isset($bendahara?->pegawaiAsn?->pangkatGolongan)
                    ? ($bendahara->pegawaiAsn->pangkatGolongan->pangkat . ', ' . $bendahara->pegawaiAsn->pangkatGolongan->golongan)
                    : '',
                'jabatan_bendahara' => $bendahara?->pegawaiAsn?->ket_jabatan ?? '',
                'tanggal_proses' => \Illuminate\Support\Carbon::parse($tanggalProses ?? now())->format('Y-m-d'),
                'status' => 'FINAL',
            ]
        );
    }
}
