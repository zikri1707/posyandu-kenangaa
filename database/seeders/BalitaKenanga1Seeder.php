<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Posyandu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BalitaKenanga1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create Posyandu Kenanga 1
        $posyandu = Posyandu::where('name', 'KENANGA 1')->first();

        if (! $posyandu) {
            $this->command->error('Posyandu KENANGA 1 tidak ditemukan. Silakan buat posyandu terlebih dahulu.');

            return;
        }

        $this->command->info('Mengimport data balita untuk Posyandu KENANGA 1...');

        $balitaData = [
            ['3275010608224411', 'A. ZAFRAN. U.R', '2022-08-06', 'L', 'RYAN. R. R', 'JL. P. NUSANTARA'],
            ['3275012008240003', 'ABHIPRAYA ATTAR SOESENO', '2024-08-20', 'L', 'ARIEF S. S', 'JL. P. BALI 2'],
            ['3275400202244084', 'ABRAHAM', '2024-02-02', 'L', 'RISWAN', 'JL. LOMBOK RAYA'],
            ['3275402204236068', 'ADITAMA A. F', '2023-04-22', 'L', 'DODI FIRMANSYAH', 'JL. P. MADURA'],
            ['3275011101223311', 'AISYAH HANIN.K', '2022-01-11', 'P', 'YUNIAR. P', 'JL. P. MADURA'],
            ['3275402503236529', 'ALBIRU R. H', '2023-03-25', 'L', 'ARIESTYA. H', 'JL. P. SUMBA'],
            ['3275404310249271', 'ALENA SALIHAH', '2024-10-03', 'P', 'GHEMA P.', 'JL. P. MADURA'],
            ['3275016903220003', 'ANINDYA ZHEA JUSTITIA', '2022-03-29', 'P', 'M. ASYASYAM', 'JL.. P. BALI 3'],
            ['3275404910235077', 'ANNISA ZAFRAN A', '2023-10-09', 'P', 'RIANGGA RIDWAN', 'JL. P. SUMBA'],
            ['3275010707220411', 'AQEELA FIDA. K', '2022-07-07', 'P', 'UJANG S', 'JL. P. MADURA'],
            ['3275011905220005', 'ARFAN SIDQI. A', '2022-05-19', 'L', 'JODI', 'JL. P. MADURA'],
            ['3275040705240001', 'ARGAWIRA ARSYSNENDRA. A', '2024-05-07', 'L', 'ALDIFA FAHRUL HUDA. SH', 'JL. P. BALI RAYA'],
            ['3275011303222211', 'ARKA', '2022-03-13', 'L', 'SUHERMAN', 'JL. P. BALI 2'],
            ['3275402902244948', 'ASHRAF FAIZAN', '2024-02-29', 'L', 'ALAN FIRMANSYAH', 'JL. P. BALI RAYA'],
            ['3275401109237265', 'AZAM RAFASYA. A', '2023-09-11', 'L', 'IRVANSYAH', 'JL. P. SUMBA'],
            ['3275406309241090', 'ELISA SHANUM A.', '2024-09-23', 'P', 'PUTRA T.A.', 'JL. P. SUMBA'],
            ['3275405510232326', 'FAIZA TAKZIA. S', '2023-10-15', 'P', 'HARDIAN', 'JL. P. SUMBA'],
            ['3275012206221111', 'FATIMAH YUMI A.', '2022-06-22', 'P', 'ANDIKA. A', 'JL. P. SUMBA'],
            ['3275402205237048', 'GALVIN ZANE. K', '2023-05-22', 'L', 'ANANDA A.', 'JL. P. MADURA'],
            ['3275401601225280', 'GANES M. D.', '2022-01-16', 'L', 'ADI RASA', 'JL. P. SUMBA'],
            ['3275016106230004', 'GLADIS ASMARALAYA', '2023-06-21', 'P', 'ADI RASA', 'JL. P. SUMBA'],
            ['3275406607259161', 'GRACE VELIORA', '2025-07-26', 'P', 'BACHTIAR', 'JL. P. BALI'],
            ['3275400910243970', 'HAURA R. R,', '2024-10-09', 'L', 'RIDWAN', 'JL. P. MADURA'],
            ['3275012105220211', 'KHAIZANU. R. AL', '2022-05-21', 'L', 'HERIYAWAN', 'JL. P. MADURA'],
            ['3275406303244019', 'KIREI HIFZA H.', '2024-03-23', 'P', 'RAHMAT TRI', 'JL. P. SUMBA RAYA'],
            ['3275404509249122', 'M MAULINA R.', '2024-09-05', 'P', 'ERA P', 'JL. P. MADURA'],
            ['3275400808247108', 'M. ALBIFARDZAN Z.', '2024-08-08', 'L', 'M. YUDA', 'JL. P.SUMBA'],
            ['3275401604231495', 'M. AZZUMAR', '2023-04-16', 'L', 'M. KAMALUDIN', 'JL. P. BALI 4'],
            ['3275402709257850', 'M. IBRAHIM', '2025-09-27', 'L', 'HARDIAN', 'JL. P. SUMBA 8'],
            ['3275406606237297', 'M. ICHSAN AL. F', '2023-06-26', 'P', 'IMAM', 'JL. P. LOMBOK'],
            ['3275400108254730', 'M. ZACKY J.', '2025-08-01', 'L', 'M. ASYSYAM. J', 'JL. P. BALI 3'],
            ['3275402005252533', 'M. ZAID U.', '2025-05-20', 'L', 'HARRY L.', 'JL. P. MADURA'],
            ['3275011009230003', 'M. ZIDAN A', '2023-09-10', 'L', 'RAFDI H', 'JL. P. MADURA'],
            ['3275012004210111', 'M.SAID. R', '2021-04-20', 'L', 'RAGIL.T', 'JL.P.SUMBA'],
            ['3275011507220111', 'MAHIRA. N. E. P. B', '2022-07-15', 'P', 'DUTA AGENG', 'JL. P. SUMBA'],
            ['3275010304220004', 'MIKAEL', '2022-04-03', 'L', 'WINSON. F. D', 'JL. P. SUMBA'],
            ['3275012204240002', 'MIKAIL AURIGA R.', '2024-04-22', 'L', 'OKI P', 'JL. P. SUMBA RAYA'],
            ['3275012612223311', 'MIKHAYLA. A. F', '2022-12-26', 'P', 'CATUR. I', 'JL.P. MADURA'],
            ['3275406403252457', 'MISHAEL N. P. A', '2025-03-24', 'P', 'NUEL K.', 'JL. P. BALI 3'],
            ['3275405902235886', 'MORIEL N. P. E.', '2023-02-19', 'P', 'NUEL KISNANDA', 'JL. P. BALI 3'],
            ['3275015307220005', 'NABILA WARNA QIRANI', '2022-07-13', 'P', 'ANDRI MOHAMAD SOFAN', 'JL. P. SUMBA'],
            ['3275012107210111', 'NATA NAEL', '2021-07-21', 'L', 'MARJOHAN', 'JL.P.SUMBA RAYA'],
            ['3275013011222211', 'RATU RALINE V. C. B', '2022-11-30', 'P', 'BACHTIAR', 'JL. P. BALI 2'],
            ['3275010302220001', 'RYUGA. A. H', '2022-02-03', 'L', 'RAHMAT. T', 'JL. P. SUMBA RAYA'],
            ['3275010409210111', 'SABHIRA', '2021-09-04', 'P', 'NURDIANSYAH', 'JL.P.SUMBA'],
            ['3275400501232088', 'SAFIQ', '2023-01-05', 'L', 'WISNU', 'JL. P. BALI 2'],
            ['3275016906210003', 'SALWA. D. Z', '2021-06-29', 'P', 'HERY. S', 'JL.P.SUMBA RAYA'],
            ['3275405004235083', 'SHAYNALA A. P', '2023-04-10', 'P', 'SYAHMI RIZAL', 'JL. P. MADURA'],
            ['3275404503255311', 'SOCA M. N.', '2025-03-05', 'P', 'IVAN B. P.', 'JL. P. SUMBA RAYA'],
            ['3275116911210003', 'TSABINA. A. L', '2021-11-29', 'P', 'ASTU. K. J', 'JL.P.MADURA'],
            ['3275012304210211', 'VALLERA DARREN. R', '2021-04-23', 'L', 'USMAN MUHAMMAD. Y', 'JL.P.BALI 3 NO.351'],
        ];

        $imported = 0;
        $skipped = 0;

        foreach ($balitaData as $data) {
            [$nik, $nama, $tglLahir, $jenisKelamin, $namaOrtu, $alamat] = $data;

            // Check if patient already exists
            $exists = Patient::where('id_number', $nik)
                ->where('posyandu_id', $posyandu->id)
                ->exists();

            if ($exists) {
                $skipped++;

                continue;
            }

            try {
                Patient::create([
                    'posyandu_id' => $posyandu->id,
                    'category' => 'balita',
                    'parent_name' => $namaOrtu,
                    'id_number' => $nik,
                    'full_name' => $nama,
                    'birth_date' => Carbon::parse($tglLahir),
                    'gender' => $jenisKelamin,
                    'address' => $alamat,
                    'phone_number' => null,
                ]);

                $imported++;
            } catch (\Exception $e) {
                $this->command->error("Gagal mengimport {$nama}: ".$e->getMessage());
            }
        }

        $this->command->info("Selesai! {$imported} balita berhasil diimport, {$skipped} dilewati (sudah ada).");
    }
}
