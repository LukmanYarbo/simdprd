<table>
    <thead>
        <tr>
            <th colspan="30" style="text-align: center; font-weight: bold;">DAFTAR PEMBAYARAN GAJI PIMPINAN DAN ANGGOTA DPRD</th>
        </tr>
        <tr>
            <th colspan="30" style="text-align: center; font-weight: bold;">BULAN {{ $bulanLabel }} {{ $tahun }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000;">NO.</th>
            <th style="font-weight: bold; border: 1px solid #000;">NAMA ANGGOTA</th>
            <th style="font-weight: bold; border: 1px solid #000;">JABATAN</th>
            <th style="font-weight: bold; border: 1px solid #000;">STS/JIWA</th>
            <th style="font-weight: bold; border: 1px solid #000;">GAJI POKOK</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. ISTRI</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. ANAK</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. BERAS</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. JABATAN</th>
            <th style="font-weight: bold; border: 1px solid #000;">UANG PAKET</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. KOMISI</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. BANMUS</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. BANGGAR</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. BALEG</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. BK</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. PANSUS</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. PANJA</th>
            <th style="font-weight: bold; border: 1px solid #000;">PEMBULATAN</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. BPJS 3%</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. JKM</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. JKK</th>
            <th style="font-weight: bold; border: 1px solid #000;">TUN. PAJAK</th>
            <th style="font-weight: bold; border: 1px solid #000;">JUMLAH KOTOR</th>
            <th style="font-weight: bold; border: 1px solid #000;">POT. BPJS 1%</th>
            <th style="font-weight: bold; border: 1px solid #000;">POT. JKK</th>
            <th style="font-weight: bold; border: 1px solid #000;">POT. JKM</th>
            <th style="font-weight: bold; border: 1px solid #000;">PAJAK</th>
            <th style="font-weight: bold; border: 1px solid #000;">POT. BPJS 1%</th>
            <th style="font-weight: bold; border: 1px solid #000;">JUMLAH POTONGAN</th>
            <th style="font-weight: bold; border: 1px solid #000;">JUMLAH BERSIH</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $totals = [
                'gaji_pokok' => 0, 'tun_istri' => 0, 'tun_anak' => 0, 'tun_beras' => 0,
                'tun_jabatan' => 0, 'uang_paket' => 0, 'tun_komisi' => 0,
                'tun_banmus' => 0, 'tun_banggar' => 0, 'tun_baleg' => 0, 'tun_bk' => 0,
                'tun_pansus' => 0, 'tun_panja' => 0, 'pembulatan' => 0,
                'tun_bpjs' => 0, 'tun_jkm' => 0, 'tun_jkk' => 0, 'tun_tax' => 0,
                'brutto' => 0,
                'pot_bpjs3' => 0, 'pot_jkk' => 0, 'pot_jkm' => 0, 'pot_tax' => 0,
                'pot_bpjs1' => 0, 'jlh_pot' => 0, 'jlh_bersih' => 0
            ];
        @endphp

        @foreach($transaksi as $t)
            @php
                $a = $t->anggota;
                $data = [
                    'gp' => $t->gaji_pokok ?? 0,
                    'istri' => $t->tunjangan_istri ?? 0,
                    'anak' => $t->tunjangan_anak ?? 0,
                    'beras' => $t->tunjangan_beras ?? 0,
                    'jab' => $t->tunjangan_jabatan ?? 0,
                    'paket' => $t->tunjangan_paket ?? 0,
                    'komisi' => $t->tunjangan_komisi ?? 0,
                    'banmus' => $t->tunjangan_banmus ?? 0,
                    'banggar' => $t->tunjangan_banggar ?? 0,
                    'baleg' => $t->tunjangan_balegda ?? 0,
                    'bk' => $t->tunjangan_bk ?? 0,
                    'pansus' => $t->tunjangan_pansus ?? 0,
                    'panja' => $t->tunjangan_panja ?? 0,
                    'bulat' => $t->pembulatan ?? 0,
                    'tun_bpjs' => $t->tunjangan_bpjs ?? 0,
                    'tun_jkm' => $t->tunjangan_jkm ?? 0,
                    'tun_jkk' => $t->tunjangan_jkk ?? 0,
                    'tun_tax' => $t->PPH21_Gaji ?? 0,
                    'brutto' => $t->brutto2 ?? 0,
                    'pot_bpjs3' => $t->potongan_bpjs ?? 0,
                    'pot_jkk' => $t->potongan_jkk ?? 0,
                    'pot_jkm' => $t->potongan_jkm ?? 0,
                    'pot_tax' => $t->potongan_pph21 ?? 0,
                    'pot_bpjs1' => $t->potongan_bpjs2 ?? 0,
                    'jlh_pot' => $t->jumlah_potongan ?? 0,
                    'jlh_bersih' => $t->brutto1 ?? 0,
                ];

                // Update totals
                $totals['gaji_pokok'] += $data['gp'];
                $totals['tun_istri'] += $data['istri'];
                $totals['tun_anak'] += $data['anak'];
                $totals['tun_beras'] += $data['beras'];
                $totals['tun_jabatan'] += $data['jab'];
                $totals['uang_paket'] += $data['paket'];
                $totals['tun_komisi'] += $data['komisi'];
                $totals['tun_banmus'] += $data['banmus'];
                $totals['tun_banggar'] += $data['banggar'];
                $totals['tun_baleg'] += $data['baleg'];
                $totals['tun_bk'] += $data['bk'];
                $totals['tun_pansus'] += $data['pansus'];
                $totals['tun_panja'] += $data['panja'];
                $totals['pembulatan'] += $data['bulat'];
                $totals['tun_bpjs'] += $data['tun_bpjs'];
                $totals['tun_jkm'] += $data['tun_jkm'];
                $totals['tun_jkk'] += $data['tun_jkk'];
                $totals['tun_tax'] += $data['tun_tax'];
                $totals['brutto'] += $data['brutto'];
                $totals['pot_bpjs3'] += $data['pot_bpjs3'];
                $totals['pot_jkk'] += $data['pot_jkk'];
                $totals['pot_jkm'] += $data['pot_jkm'];
                $totals['pot_tax'] += $data['pot_tax'];
                $totals['pot_bpjs1'] += $data['pot_bpjs1'];
                $totals['jlh_pot'] += $data['jlh_pot'];
                $totals['jlh_bersih'] += $data['jlh_bersih'];

                $status = ($a->status_perkawinan == 'Menikah' ? 'K' : 'T') . '/' . ($a->jumlah_istri ?? 0) . '/' . ($a->jumlah_anak ?? 0);
            @endphp
            <tr>
                <td style="border: 1px solid #000;">{{ $no++ }}</td>
                <td style="border: 1px solid #000;">{{ strtoupper($a->nama_anggota) }}</td>
                <td style="border: 1px solid #000;">{{ strtoupper($a->jabatan->nama ?? '') }}</td>
                <td style="border: 1px solid #000;">{{ $status }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['gp'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['istri'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['anak'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['beras'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['jab'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['paket'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['komisi'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['banmus'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['banggar'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['baleg'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['bk'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['pansus'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['panja'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['bulat'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['tun_bpjs'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['tun_jkm'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['tun_jkk'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['tun_tax'] }}</td>
                <td style="border: 1px solid #000; text-align: right; font-weight: bold;">{{ $data['brutto'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['pot_bpjs3'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['pot_jkk'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['pot_jkm'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['pot_tax'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['pot_bpjs1'] }}</td>
                <td style="border: 1px solid #000; text-align: right; font-weight: bold;">{{ $data['jlh_pot'] }}</td>
                <td style="border: 1px solid #000; text-align: right; font-weight: bold;">{{ $data['jlh_bersih'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="font-weight: bold; border: 1px solid #000; text-align: center;">TOTAL</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['gaji_pokok'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_istri'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_anak'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_beras'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_jabatan'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['uang_paket'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_komisi'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_banmus'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_banggar'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_baleg'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_bk'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_pansus'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_panja'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['pembulatan'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_bpjs'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_jkm'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_jkk'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['tun_tax'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['brutto'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['pot_bpjs3'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['pot_jkk'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['pot_jkm'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['pot_tax'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['pot_bpjs1'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['jlh_pot'] }}</td>
            <td style="font-weight: bold; border: 1px solid #000; text-align: right;">{{ $totals['jlh_bersih'] }}</td>
        </tr>
    </tfoot>
</table>
