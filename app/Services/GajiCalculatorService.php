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
        $this->param      = ParameterGaji::where('status', 'Y')->first();
        $this->tunUmum    = TunjanganUmum::where('status', 'Y')->first();
        $this->tunPerum   = TunjanganPerumahan::where('status', 'Y')->first();
        $this->tunTrans   = TunjanganTransportasi::where('status', 'Y')->first();
        $this->tunTki     = TunjanganKomunikasiIntensif::where('status', 'Y')->first();
        $this->pajakParam = TarifPajak::where('status', 'Y')->with('lapisPajak')->first();
        $this->potongan   = Potongan::first();

        return $this->param && $this->tunUmum && $this->tunPerum
            && $this->tunTrans && $this->tunTki && $this->pajakParam
            && $this->potongan;
    }

    public function getMissingParams(): array
    {
        $missing = [];
        if (!$this->param)      $missing[] = 'Parameter Gaji Belum ada';
        if (!$this->tunUmum)    $missing[] = 'Tunjangan Umum Belum ada';
        if (!$this->tunPerum)   $missing[] = 'Tunjangan Perumahan Belum ada';
        if (!$this->tunTrans)   $missing[] = 'Tunjangan Transportasi Belum ada';
        if (!$this->tunTki)     $missing[] = 'Tunjangan Komunikasi Intensif Belum ada';
        if (!$this->pajakParam) $missing[] = 'Tarif Pajak Belum ada';
        if (!$this->potongan)   $missing[] = 'Data Potongan Belum ada';
        return $missing;
    }

    // ===========================
    // DISPATCHER
    // ===========================
    public function hitungGaji(Anggota $anggota, string $blnThn, string $metodePajak = 'ter'): array
    {
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
        $p   = $this->param;
        $tu  = $this->tunUmum;
        $pt  = $this->tunPerum;
        $tt  = $this->tunTrans;
        $tki = $this->tunTki;
        $pot = $this->potongan;

        $GAJIPOKOK           = $p->gajipokok_ketua;
        $PERSENWKL           = $p->persen_gapokwakil;
        $PERSENANG           = $p->persen_gapokanggota;
        $PERSEN_TUNJABKETUA  = $p->persen_tunjabketua;
        $PERSEN_TUNJABWKL    = $p->persen_tunjabwakil;
        $PERSEN_TUNJABANG    = $p->persen_tunjabanggota;
        $PERSEN_TUNKETUAALEG = $p->persen_tunketua_aleg;
        $PERSEN_TUNWKLALEG   = $p->persen_tunwakil_aleg;
        $PERSEN_TUNSEKALEG   = $p->persen_tunsek_aleg;
        $PERSEN_TUNANGALEG   = $p->persen_tunanggota_aleg;
        $PERSEN_UANGPAKET    = $p->persen_uangpaket;

        $TUN_BERAS   = $tu->tunjangan_beras;
        $JLH_BERAS   = $tu->jumlah_beras;
        $PERSEN_ANAK = $tu->tunjangan_anak_persen;
        $PERSEN_IS   = $tu->tunjangan_istri_persen;

        $TUNPERUM_KETUA = $pt->nilai_tunjangan_ketua;
        $TUNPERUM_WAKIL = $pt->nilai_tunjangan_wakil;
        $TUNPERUM_ANG   = $pt->nilai_tunjangan_anggota;

        $TUNTRANS_KETUA = $tt->nilai_tunjangan_ketua;
        $TUNTRANS_WAKIL = $tt->nilai_tunjangan_wakil;
        $TUNTRANS_ANG   = $tt->nilai_tunjangan_anggota;

        $TUNTKI_VAL = $tki->nilai_tunjangan_tki;

        $TUN_BPJS   = $pot->tunjangan_bpjs;
        $POT_BPJS   = $pot->potongan_bpjs;
        $POT_JKK    = $pot->jkk;
        $POT_JKM    = $pot->jkm;
        $MAX_JKKJKM = $pot->maks_jkkjkm;

        $ID_DPRD      = $anggota->id_dprd;
        $JLH_IS       = $anggota->jmlh_istri ?? 0;
        $JLH_ANAK     = $anggota->jmlh_anak ?? 0;
        $skRow        = $anggota->statusKawin;
        $STS_KAWIN    = $skRow ? $skRow->kode : 'T';
        $KD_BPJS      = $anggota->status_bpjs;
        $KD_JKK       = $anggota->status_jkk;
        $KD_JKM       = $anggota->status_jkm;
        $KD_TUN_PERUM = $anggota->status_tjgn_perum;
        $KD_TUN_TRANS = $anggota->status_tjgn_transport;
        $ID_KOMISI    = $anggota->id_komisi;
        $ID_BANGGAR   = $anggota->id_banggar;
        $ID_BANMUS    = $anggota->id_banmus;
        $ID_BALEGDA   = $anggota->id_balegda;
        $ID_BK        = $anggota->id_bk;
        $ID_PANSUS    = $anggota->id_pansus;
        $ID_PANJA     = $anggota->id_panja;

        $GAPOK    = 0; $TI = 0; $TA = 0; $TB = 0; $TUNJAB = 0;
        $TUNPERUM = 0; $TUNTRANS = 0; $TUNTKI = 0; $UANGPAKET = 0;
        $TUNBPJS  = 0; $POTBPJS  = 0; $JKK = 0; $JKM = 0;
        $JLH_PEG  = 1;
        $GapokKetua = $GAJIPOKOK;

        if ($ID_DPRD == 1) {
            $GAPOK    = $GapokKetua;
            $TUNJAB   = $GAPOK * ($PERSEN_TUNJABKETUA / 100);
            $TUNPERUM = ($KD_TUN_PERUM == 'Y') ? $TUNPERUM_KETUA : 0;
            $TUNTRANS = ($KD_TUN_TRANS == 'Y') ? $TUNTRANS_KETUA : 0;
        } elseif ($ID_DPRD == 2) {
            $GAPOK    = $GapokKetua * ($PERSENWKL / 100);
            $TUNJAB   = $GAPOK * ($PERSEN_TUNJABWKL / 100);
            $TUNPERUM = ($KD_TUN_PERUM == 'Y') ? $TUNPERUM_WAKIL : 0;
            $TUNTRANS = ($KD_TUN_TRANS == 'Y') ? $TUNTRANS_WAKIL : 0;
        } else { // Anggota (ID_DPRD >= 3)
            $GAPOK    = $GapokKetua * ($PERSENANG / 100);
            $TUNJAB   = $GAPOK * ($PERSEN_TUNJABANG / 100);
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

        $TI        = (($PERSEN_IS / 100) * $GAPOK) * $JLH_IS;
        $TA        = (($PERSEN_ANAK / 100) * $GAPOK) * $JLH_ANAK;
        $TB        = ($TUN_BERAS * $JLH_BERAS) * (1 + $JLH_IS + $JLH_ANAK);
        $TUNTKI    = $TUNTKI_VAL;
        $UANGPAKET = $GAPOK * ($PERSEN_UANGPAKET / 100);
        $JLH_JIWA  = $JLH_PEG + $JLH_IS + $JLH_ANAK;

        if ($KD_BPJS == 'Y') {
            $baseGapok = $GAPOK + $TI + $TA + $TUNJAB;
            $TUNBPJS   = ($TUN_BPJS / 100) * $baseGapok;
            $POTBPJS   = ($POT_BPJS / 100) * $baseGapok;
        }

        $TunJabDPRD_Ketua = $GapokKetua * ($PERSEN_TUNJABKETUA / 100);
        $TUNKOMISI  = $this->hitungTunAleg($ID_KOMISI,  $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBANGGAR = $this->hitungTunAleg($ID_BANGGAR, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBANMUS  = $this->hitungTunAleg($ID_BANMUS,  $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBALEGDA = $this->hitungTunAleg($ID_BALEGDA, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBK      = $this->hitungTunAleg($ID_BK,      $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNPANSUS  = $this->hitungTunAleg($ID_PANSUS,  $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNPANJA   = $this->hitungTunAleg($ID_PANJA,   $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);

        // TOTALGAJI = komponen gaji utama (tidak termasuk tunjangan perumahan/transport/TKI)
        $TOTALGAJI = $GAPOK + $TI + $TA + $TB + $TUNJAB
            + $TUNKOMISI + $TUNBANGGAR + $TUNBANMUS + $TUNBALEGDA
            + $TUNBK + $TUNPANSUS + $TUNPANJA
            + $UANGPAKET + $TUNBPJS + $JKK + $JKM;

        // TUNJANGAN = perumahan + transportasi + TKI
        $TUNJANGAN = $TUNPERUM + $TUNTRANS + $TUNTKI;

        $BRUTTO = $TOTALGAJI + $TUNJANGAN;

        // Status PTKP
        $JLH_TG     = $JLH_IS + $JLH_ANAK;
        $statusPtkp = ($STS_KAWIN === 'K' ? 'K' : 'TK') . '/' . min($JLH_TG, 3);

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
        $terArray  = config('ter_pajak.kategori_' . strtolower($kategoriTer), []);
        $persenTer = 0;
        foreach ($terArray as $layer) {
            if ($BRUTTO <= $layer['max']) {
                $persenTer = $layer['persen'];
                break;
            }
        }

        $PPhTER          = round($BRUTTO * ($persenTer / 100));
        $rasioTunjangan  = ($TOTALGAJI > 0) ? ($TUNJANGAN / $TOTALGAJI) : 0;
        $PPH21_Gaji      = round($PPhTER * $rasioTunjangan);
        $PPH21_Tunjangan = $PPhTER - $PPH21_Gaji;
        $TOTAL_BERSIH    = $BRUTTO - $PPH21_Tunjangan;

        $DETAIL_PAJAK = [
            'metode'           => 'ter',
            'Total Pendapatan' => $BRUTTO,
            'Gaji'             => $TOTALGAJI,
            'Tunjangan'        => $TUNJANGAN,
            'status_ptkp'      => $statusPtkp,
            'kategori_ter'     => 'Kategori ' . $kategoriTer,
            'persen_ter'       => $persenTer,
            'pph_sebulan'      => $PPhTER,
            'pph_gaji'         => $PPH21_Gaji,
            'pph_tunjangan'    => $PPH21_Tunjangan,
        ];

        return [
            'bln_thn'                  => $blnThn,
            'id_anggota'               => $anggota->id,
            'jumlah_is'                => $JLH_IS,
            'jumlah_anak'              => $JLH_ANAK,
            'status_kawin'             => $STS_KAWIN,
            'jumlah_pegawai'           => $JLH_PEG,
            'jumlah_jiwa'              => $JLH_JIWA,
            'gaji_pokok'               => (int) round($GAPOK),
            'tunjangan_anak'           => (int) round($TA),
            'tunjangan_istri'          => (int) round($TI),
            'tunjangan_beras'          => (int) round($TB),
            'tunjangan_paket'          => (int) round($UANGPAKET),
            'tunjangan_jabatan'        => (int) round($TUNJAB),
            'tunjangan_komisi'         => (int) round($TUNKOMISI),
            'tunjangan_banggar'        => (int) round($TUNBANGGAR),
            'tunjangan_banmus'         => (int) round($TUNBANMUS),
            'tunjangan_balegda'        => (int) round($TUNBALEGDA),
            'tunjangan_bk'             => (int) round($TUNBK),
            'tunjangan_pansus'         => (int) round($TUNPANSUS),
            'tunjangan_panja'          => (int) round($TUNPANJA),
            'pembulatan'               => 0,
            'brutto1'                  => (int) round($BRUTTO),
            'brutto2'                  => 0,
            'tunjangan_pph21'          => (int) round($PPhTER),
            'tunjangan_bpjs'           => (int) round($TUNBPJS),
            'tunjangan_jkk'            => (int) round($JKK),
            'tunjangan_jkm'            => (int) round($JKM),
            'Kategori_TER'             => $kategoriTer,
            'Nilai_TER'                => $persenTer,
            'PPH21_Gaji'               => (int) round($PPH21_Gaji),
            'PPh21_Tunjangan'          => (int) round($PPH21_Tunjangan),
            'potongan_pph21'           => (int) round($PPhTER),
            'potongan_bpjs'            => (int) round($POTBPJS),
            'potongan_bpjs2'           => 0,
            'potongan_jkk'             => (int) round($JKK),
            'potongan_jkm'             => (int) round($JKM),
            'nilai_netto'              => 0,
            'tunjangan_perumahan'      => (int) round($TUNPERUM),
            'tunjangan_transportasi'   => (int) round($TUNTRANS),
            'tunjangan_tki'            => (int) round($TUNTKI),
            'tunjangan_reses'          => 0,
            'potonganpph_perumahan'    => 0,
            'potonganpph_transportasi' => 0,
            'potonganpph_tki'          => 0,
            'potonganpph_reses'        => 0,
            'nilai_gajitunjangan'      => (int) round($BRUTTO),
            'total_potongan1'          => 0,
            'total_potongan2'          => 0,
            'jumlah_bersih'            => (int) round($TOTAL_BERSIH),
            'detail_pajak'             => $DETAIL_PAJAK,
        ];
    }

    // ===========================
    // METODE LAPIS PAJAK LAMA (Pasal 17)
    // ===========================
    protected function hitungGajiLapis(Anggota $anggota, string $blnThn): array
    {
        $p   = $this->param;
        $tu  = $this->tunUmum;
        $pt  = $this->tunPerum;
        $tt  = $this->tunTrans;
        $tki = $this->tunTki;
        $px  = $this->pajakParam;
        $pot = $this->potongan;

        $GAJIPOKOK           = $p->gajipokok_ketua;
        $PERSENWKL           = $p->persen_gapokwakil;
        $PERSENANG           = $p->persen_gapokanggota;
        $PERSEN_TUNJABKETUA  = $p->persen_tunjabketua;
        $PERSEN_TUNJABWKL    = $p->persen_tunjabwakil;
        $PERSEN_TUNJABANG    = $p->persen_tunjabanggota;
        $PERSEN_TUNKETUAALEG = $p->persen_tunketua_aleg;
        $PERSEN_TUNWKLALEG   = $p->persen_tunwakil_aleg;
        $PERSEN_TUNSEKALEG   = $p->persen_tunsek_aleg;
        $PERSEN_TUNANGALEG   = $p->persen_tunanggota_aleg;
        $PERSEN_UANGPAKET    = $p->persen_uangpaket;

        $TUN_BERAS   = $tu->tunjangan_beras;
        $JLH_BERAS   = $tu->jumlah_beras;
        $PERSEN_ANAK = $tu->tunjangan_anak_persen;
        $PERSEN_IS   = $tu->tunjangan_istri_persen;

        $TUNPERUM_KETUA = $pt->nilai_tunjangan_ketua;
        $TUNPERUM_WAKIL = $pt->nilai_tunjangan_wakil;
        $TUNPERUM_ANG   = $pt->nilai_tunjangan_anggota;

        $TUNTRANS_KETUA = $tt->nilai_tunjangan_ketua;
        $TUNTRANS_WAKIL = $tt->nilai_tunjangan_wakil;
        $TUNTRANS_ANG   = $tt->nilai_tunjangan_anggota;

        $TUNTKI_VAL    = $tki->nilai_tunjangan_tki;

        $PTKP          = $px->ptkp;
        $PTKP_PLUS     = $px->tambahan_ptkp_tanggungan;
        $BIAYA_JAB     = $px->persen_biaya_jabatan;
        $MAX_BIAYA_JAB = $px->max_biaya_jabatan;
        $lapisPajak    = $px->lapisPajak->sortBy('dari');

        $TUN_BPJS   = $pot->tunjangan_bpjs;
        $POT_BPJS   = $pot->potongan_bpjs;
        $MAX_BPJS   = $pot->maksimal_potongan_bpjs;
        $POT_JKK    = $pot->jkk;
        $POT_JKM    = $pot->jkm;
        $MAX_JKKJKM = $pot->maks_jkkjkm;
        $POT_PPH15  = $pot->pot_pph;

        $ID_DPRD      = $anggota->id_dprd;
        $JLH_IS       = $anggota->jmlh_istri ?? 0;
        $JLH_ANAK     = $anggota->jmlh_anak ?? 0;
        $skRow        = $anggota->statusKawin;
        $STS_KAWIN    = $skRow ? $skRow->kode : 'T';
        $KD_BPJS      = $anggota->status_bpjs;
        $KD_JKK       = $anggota->status_jkk;
        $KD_JKM       = $anggota->status_jkm;
        $KD_TUN_PERUM = $anggota->status_tjgn_perum;
        $KD_TUN_TRANS = $anggota->status_tjgn_transport;
        $ID_KOMISI    = $anggota->id_komisi;
        $ID_BANGGAR   = $anggota->id_banggar;
        $ID_BANMUS    = $anggota->id_banmus;
        $ID_BALEGDA   = $anggota->id_balegda;
        $ID_BK        = $anggota->id_bk;
        $ID_PANSUS    = $anggota->id_pansus;
        $ID_PANJA     = $anggota->id_panja;

        $GAPOK    = 0; $TI = 0; $TA = 0; $TB = 0; $TUNJAB = 0;
        $TUNPERUM = 0; $TUNTRANS = 0; $TUNTKI = 0; $UANGPAKET = 0;
        $JLH_PEG  = 1; $JLH_JIWA = 0;
        $TUNBPJS  = 0; $POTBPJS  = 0; $JKK = 0; $JKM = 0;
        $GapokKetua    = $GAJIPOKOK;
        $TUNPERUM_VAL  = 0; $TUNTRANS_VAL = 0;
        $PERSEN_TUNJAB = 0; $PERSEN_GAPOK = 100;

        if ($ID_DPRD == 1) {
            $PERSEN_TUNJAB = $PERSEN_TUNJABKETUA;
            $TUNPERUM_VAL  = $TUNPERUM_KETUA;
            $TUNTRANS_VAL  = $TUNTRANS_KETUA;
        } elseif ($ID_DPRD == 2) {
            $PERSEN_GAPOK  = $PERSENWKL;
            $PERSEN_TUNJAB = $PERSEN_TUNJABWKL;
            $TUNPERUM_VAL  = $TUNPERUM_WAKIL;
            $TUNTRANS_VAL  = $TUNTRANS_WAKIL;
        } else {
            $PERSEN_GAPOK  = $PERSENANG;
            $PERSEN_TUNJAB = $PERSEN_TUNJABANG;
            $TUNPERUM_VAL  = $TUNPERUM_ANG;
            $TUNTRANS_VAL  = $TUNTRANS_ANG;
        }

        $GAPOK     = $GapokKetua * ($PERSEN_GAPOK / 100);
        $TI        = (($PERSEN_IS / 100) * $GAPOK) * $JLH_IS;
        $TA        = (($PERSEN_ANAK / 100) * $GAPOK) * $JLH_ANAK;
        $TB        = ($TUN_BERAS * $JLH_BERAS) * (1 + $JLH_IS + $JLH_ANAK);
        $TUNJAB    = $GAPOK * ($PERSEN_TUNJAB / 100);
        $TUNPERUM  = ($KD_TUN_PERUM == 'Y') ? $TUNPERUM_VAL : 0;
        $TUNTRANS  = ($KD_TUN_TRANS == 'Y') ? $TUNTRANS_VAL : 0;
        $TUNTKI    = $TUNTKI_VAL;
        $UANGPAKET = $GAPOK * ($PERSEN_UANGPAKET / 100);
        $JLH_JIWA  = 1 + $JLH_IS + $JLH_ANAK;

        if ($KD_BPJS == 'Y') {
            $baseGapok = $GAPOK + $TI + $TA + $TUNJAB;
            $TUNBPJS   = ($TUN_BPJS / 100) * $baseGapok;
            $POTBPJS   = ($POT_BPJS / 100) * $baseGapok;
        }
        if ($KD_JKK == 'Y') {
            $JKK = ($GAPOK < $MAX_JKKJKM) ? $MAX_JKKJKM * ($POT_JKK / 100) : $GAPOK * ($POT_JKK / 100);
        }
        if ($KD_JKM == 'Y') {
            $JKM = ($GAPOK < $MAX_JKKJKM) ? $MAX_JKKJKM * ($POT_JKM / 100) : $GAPOK * ($POT_JKM / 100);
        }

        $TunJabDPRD_Ketua = $GapokKetua * ($PERSEN_TUNJABKETUA / 100);
        $TUNKOMISI  = $this->hitungTunAleg($ID_KOMISI,  $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBANGGAR = $this->hitungTunAleg($ID_BANGGAR, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBANMUS  = $this->hitungTunAleg($ID_BANMUS,  $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBALEGDA = $this->hitungTunAleg($ID_BALEGDA, $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNBK      = $this->hitungTunAleg($ID_BK,      $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNPANSUS  = $this->hitungTunAleg($ID_PANSUS,  $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);
        $TUNPANJA   = $this->hitungTunAleg($ID_PANJA,   $TunJabDPRD_Ketua, $PERSEN_TUNKETUAALEG, $PERSEN_TUNWKLALEG, $PERSEN_TUNSEKALEG, $PERSEN_TUNANGALEG);

        $POTPPH_TUNPERUM = ($POT_PPH15 / 100) * $TUNPERUM;
        $POTPPH_TUNTRANS = ($POT_PPH15 / 100) * $TUNTRANS;
        $POTPPH_TUNTKI   = ($POT_PPH15 / 100) * $TUNTKI;
        $TOT_POT_PPH15   = $POTPPH_TUNPERUM + $POTPPH_TUNTRANS + $POTPPH_TUNTKI;

        $BRUTTO = $GAPOK + $TI + $TA + $TB + $TUNJAB
            + $TUNKOMISI + $TUNBANGGAR + $TUNBANMUS + $TUNBALEGDA
            + $TUNBK + $TUNPANSUS + $TUNPANJA
            + $UANGPAKET + $TUNBPJS + $JKK + $JKM;

        $POT        = $JKK + $JKM + $TUNBPJS + $POTBPJS;
        $JLH1       = $BRUTTO - $POT;
        $BRUTTO1    = ceil($JLH1 / 100) * 100;
        $PEMBULATAN = $BRUTTO1 - $JLH1;
        $BRUTTO2    = $BRUTTO + $PEMBULATAN;

        $JLH_TG     = $JLH_IS + $JLH_ANAK;
        $statusPtkp = ($STS_KAWIN === 'K' ? 'K' : 'TK') . '/' . min($JLH_TG, 3);

        $BJ_hitung = ($BIAYA_JAB / 100) * $BRUTTO2;
        $BJ        = min($BJ_hitung, $MAX_BIAYA_JAB);
        $KRG       = $BRUTTO2 - $BJ;
        $STHN      = $KRG * 12;
        $PJK1      = $PTKP + ($JLH_TG * $PTKP_PLUS);
        $PKP_KOTOR = $STHN - $PJK1;
        $PKP       = floor($PKP_KOTOR / 1000) * 1000;
        $PKP2      = $this->hitungPKP2($PKP, $lapisPajak);
        $PPH_STHN  = floor($PKP2);
        $TOT_PAJAK = max(0, $PPH_STHN);
        $PPH       = $TOT_PAJAK / 12;
        $POT_PPH   = $TOT_PAJAK / 12;

        $DETAIL_PAJAK = [
            'metode'           => 'lapis',
            'bruto_sebulan'    => $BRUTTO2,
            'persen_biaya_jab' => $BIAYA_JAB,
            'biaya_jabatan'    => $BJ_hitung,
            'max_biaya_jab'    => $MAX_BIAYA_JAB,
            'neto_sebulan'     => $KRG,
            'neto_setahun'     => $STHN,
            'status_ptkp'      => $statusPtkp,
            'nilai_ptkp'       => $PJK1,
            'pkp_kotor'        => $PKP_KOTOR,
            'pkp_pembulatan'   => $PKP,
            'pph_setahun'      => $TOT_PAJAK,
            'pph_sebulan'      => $PPH,
        ];

        $TOT_KOTOR    = $BRUTTO + $PPH + $PEMBULATAN;
        $TOTAL        = $TOT_KOTOR - ($POT_PPH + $POT);
        $TOT_POT2     = $POT + $POT_PPH;
        $TOT_GAJI_TUN = $TOT_KOTOR + $TUNPERUM + $TUNTRANS + $TUNTKI;
        $TOT_POT      = $TOT_POT_PPH15 + $TOT_POT2;
        $TOTAL_BERSIH = $TOT_GAJI_TUN - $TOT_POT;
        $DETAIL_PAJAK['bruto_sebulan'] = $TOT_KOTOR;

        return [
            'bln_thn'                  => $blnThn,
            'id_anggota'               => $anggota->id,
            'jumlah_is'                => $JLH_IS,
            'jumlah_anak'              => $JLH_ANAK,
            'status_kawin'             => $STS_KAWIN,
            'jumlah_pegawai'           => $JLH_PEG,
            'jumlah_jiwa'              => $JLH_JIWA,
            'gaji_pokok'               => (int) round($GAPOK),
            'tunjangan_anak'           => (int) round($TA),
            'tunjangan_istri'          => (int) round($TI),
            'tunjangan_beras'          => (int) round($TB),
            'tunjangan_paket'          => (int) round($UANGPAKET),
            'tunjangan_jabatan'        => (int) round($TUNJAB),
            'tunjangan_komisi'         => (int) round($TUNKOMISI),
            'tunjangan_banggar'        => (int) round($TUNBANGGAR),
            'tunjangan_banmus'         => (int) round($TUNBANMUS),
            'tunjangan_balegda'        => (int) round($TUNBALEGDA),
            'tunjangan_bk'             => (int) round($TUNBK),
            'tunjangan_pansus'         => (int) round($TUNPANSUS),
            'tunjangan_panja'          => (int) round($TUNPANJA),
            'pembulatan'               => (int) round($PEMBULATAN),
            'brutto1'                  => (int) round($BRUTTO1),
            'brutto2'                  => (int) round($TOT_KOTOR),
            'tunjangan_pph21'          => (int) round($PPH),
            'tunjangan_bpjs'           => (int) round($TUNBPJS),
            'tunjangan_jkk'            => (int) round($JKK),
            'tunjangan_jkm'            => (int) round($JKM),
            'Kategori_TER'             => null,
            'Nilai_TER'                => null,
            'PPH21_Gaji'               => 0,
            'PPh21_Tunjangan'          => 0,
            'potongan_pph21'           => (int) round($POT_PPH),
            'potongan_bpjs'            => (int) round($POTBPJS),
            'potongan_bpjs2'           => 0,
            'potongan_jkk'             => (int) round($JKK),
            'potongan_jkm'             => (int) round($JKM),
            'nilai_netto'              => (int) round($TOTAL),
            'tunjangan_perumahan'      => (int) round($TUNPERUM),
            'tunjangan_transportasi'   => (int) round($TUNTRANS),
            'tunjangan_tki'            => (int) round($TUNTKI),
            'tunjangan_reses'          => 0,
            'potonganpph_perumahan'    => (int) round($POTPPH_TUNPERUM),
            'potonganpph_transportasi' => (int) round($POTPPH_TUNTRANS),
            'potonganpph_tki'          => (int) round($POTPPH_TUNTKI),
            'potonganpph_reses'        => 0,
            'nilai_gajitunjangan'      => (int) round($TOT_GAJI_TUN),
            'total_potongan1'          => (int) round($TOT_POT),
            'total_potongan2'          => (int) round($TOT_POT2),
            'jumlah_bersih'            => (int) round($TOTAL_BERSIH),
            'detail_pajak'             => $DETAIL_PAJAK,
        ];
    }

    // ===========================
    // HELPERS
    // ===========================
    protected function hitungTunAleg(?int $jabatanId, float $tunJabKetua, float $pKetua, float $pWakil, float $pSek, float $pAnggota): float
    {
        if (!$jabatanId) return 0;

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
        if ($PKP <= 0) return 0;

        $lapisArr    = $lapisPajak->values()->all();
        $jumlahLapis = count($lapisArr);

        if ($jumlahLapis === 0) return 0;

        $PKP2 = 0;
        $sisa = $PKP;

        foreach ($lapisArr as $i => $lapis) {
            $dari   = $lapis->dari;
            $sampai = $lapis->sampai; // null = tidak terbatas
            $persen = $lapis->persen;

            if ($sisa <= 0) break;

            if ($sampai === null) {
                $kena = $sisa;
            } else {
                $batas = $sampai - $dari;
                $kena  = min($sisa, $batas);
            }

            $PKP2 += $kena * ($persen / 100);
            $sisa -= $kena;
        }

        return $PKP2;
    }
}
