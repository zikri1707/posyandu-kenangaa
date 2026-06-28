<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Posyandu;
use App\Services\NutritionCalculatorService;
use Carbon\Carbon;

class DataBalitaSeeder extends Seeder
{
    public function run()
    {
        $calculator = app(NutritionCalculatorService::class);
        $posyandu = Posyandu::first();
        $posyanduId = $posyandu ? $posyandu->id : 1;

        // ==========================================
        // 1. DATA JANUARI 2026 (5 Januari 2026)
        // ==========================================
        $janDate = '2026-01-05';
        $janData = [
            // [Nama, Ortu, RT/RW, L/P, Tgl Lahir, BB, TB, Status]
            ['Ciara Hafza', 'Djaka S', '01/11', 'P', '2020-11-11', 15.7, 98, 'T'],
            ['Aisyah Hanin', 'Yuniar P', '04/11', 'P', '2020-11-30', 20, 99, 'N'],
            ['Chayra Aretha', 'Almi T', '02/11', 'P', '2020-11-30', 16.5, 101, 'T'],
            ['Alfian Zafran', 'M. Asysyam', '03/11', 'L', '2021-02-03', 21.2, 111, 'T'],
            ['M. Said', 'Raqil', '02/11', 'L', '2021-04-20', 15.7, 96, 'T'],
            ['Vallera Darren R', 'Usman', '02/11', 'L', '2021-04-23', 15.8, 101, 'T'],
            ['Salwa D.Z', 'Heri S', '01/11', 'P', '2020-02-29', 32, 111, 'T'],
            ['Nata Noel', 'Marjohan', '01/11', 'L', '2021-06-21', 18.5, 99, 'T'],
            ['Sabhira', 'Murdiansyah', '01/11', 'P', '2021-02-28', 16.4, 97, 'T'],
            ['Tsabina A.L', 'Asty K.2', '03/11', 'P', '2021-11-29', 14.5, 96, 'T'],
            ['Ganesh M.D', 'Adi Rosa', '01/11', 'L', '2022-01-06', 12.4, 93, 'T'],
            ['Ryuga A.H', 'Rahmat T', '02/11', 'L', '2022-02-03', 19.5, 100, 'T'],
            ['Anindya Dhea', 'M. Asysyam', '02/11', 'P', '2022-02-28', 15.7, 93, 'T'],
            ['Mikael', 'Winson', '01/11', 'L', '2022-04-03', 14, 96, 'N'],
            ['Arka', 'Suherman', '02/11', 'L', '2022-05-21', 12.7, 96, 'T'],
            ['Khaizanu', 'Heriyawan', '03/11', 'L', '2022-05-21', 16.9, 96, 'T'],
            ['Fatimah Yuni', 'A. Akbari', '01/11', 'P', '2022-05-22', 13.5, 90, 'T'],
            ['Arfan Sidqi', 'Jodi', '08/11', 'L', '2022-05-29', 19.4, 99, 'T'],
            ['Nabila Warna', 'Adri M.S', '01/11', 'P', '2022-01-03', 11.5, 89, 'T'],
            ['Assela Fida', 'Ujang', '04/11', 'P', '2022-03-07', 13, 92, 'N'],
            ['Mahira N.P.E.A', 'Duta A', '02/11', 'P', '2022-03-13', 16.8, 89, 'T'],
            ['A. Zafran U.R', 'Byan R.R', '04/11', 'L', '2022-03-06', 16.1, 99, 'T'],
            ['Ratu Raline VCB', 'Bachtiar', '02/11', 'P', '2022-10-07', 13.8, 92, 'T'],
            ['Mikhayla A.F', 'Catur I', '03/11', 'P', '2022-02-26', 12.5, 91, 'O'],
            ['Safia', 'Wisnu', '02/11', 'L', '2023-01-05', 17.4, 97, 'T'],
            ['Moriel N.P.A', 'Muel K', '02/11', 'P', '2023-03-25', 10.7, 86, 'T'],
            ['Albiru', 'Ariestya', '01/11', 'L', '2023-03-25', 12.5, 88, 'T'],
            ['Shaynala', 'Syahmi', '03/11', 'P', '2023-04-10', 12.5, 92, 'T'],
            ['M. Azzumar', 'M. Kamaludin', '02/11', 'L', '2023-04-16', 11.5, 84, 'T'],
            ['Adi Tama', 'Dodi F', '03/11', 'L', '2023-04-22', 13.5, 92, 'N'],
            ['Calvin Zane', 'Ananda', '04/11', 'L', '2023-05-22', 13, 92, 'N'],
            ['Jasmin', 'Oji', '04/11', 'P', '2023-06-14', 12.5, 78, 'T'],
            ['Bladis A', 'Adi Rosa', '01/11', 'P', '2023-06-21', 10.4, 85, 'T'],
            ['M. Ichsan A', 'Imam', '02/11', 'L', '2023-06-26', 12.8, 93, 'T'],
            ['Askara A', 'Arindra', '02/11', 'P', '2023-09-20', 11.8, 75, 'T'],
            ['Azzam Ropasisa', 'Irwansyah', '01/11', 'L', '2023-09-11', 10.7, 82, 'T'],
            ['M. Zidan A', 'Rafdi H', '03/11', 'L', '2023-09-10', 12.7, 85, 'T'],
            ['Faiza Takzia', 'Hardian', '01/11', 'P', '2023-10-15', 12.2, 89, 'T'],
            ['Annisa Zafran', 'Riangga', '01/11', 'P', '2023-11-09', 10.7, 85, 'T'],
            ['Abraham', 'Riswan', '02/11', 'L', '2024-02-29', 11.9, 87, 'T'],
            ['Ashraf Faisan', 'Alan F', '04/11', 'L', '2024-02-20', 11, 80, 'N'],
            ['Serenata U.T', 'Theresia', '04/11', 'P', '2024-08-20', 11.8, 93, 'T'],
            ['Kirai Hafza H', 'Rahmat Tri', '02/11', 'P', '2024-03-24', 13.3, 92, 'T'],
            ['Oki', 'Mikail', '02/11', 'L', '2024-04-22', 10.9, 85, 'T'],
            ['Angawira A.A', 'Aldifa', '02/11', 'L', '2024-05-07', 11.5, 82, 'T'],
            ['M. Albifarzan', 'M. Yuda', '01/11', 'L', '2024-08-06', 10, 75, 'T'],
            ['Abhiprana A.S', 'Arief S.S', '02/11', 'P', '2024-08-20', 9.5, 73, 'T'],
            ['M. Maulia R', 'Eva K', '03/11', 'L', '2024-09-05', 9.5, 72, 'T'],
            ['Elisa Shanum A', 'Putra T.A', '01/11', 'P', '2024-09-23', 8.9, 73, 'T'],
            ['Alena Shalihah', 'Ghena P', '04/11', 'P', '2024-10-03', 8.5, 99, 'T'],
            ['Haura R.R', 'Ridwan', '04/11', 'P', '2024-10-04', 9, 76, 'N'],
            ['Barra Al Fatih', 'Wiwit A', '01/11', 'L', '2024-10-01', 14, 80, 'N'],
            ['Soca M.N', 'Ivan B.P', '01/11', 'P', '2025-06-05', 9.3, 69, 'T'],
            ['Mishael N.P.A', 'Muel K', '02/11', 'P', '2025-03-24', 6.9, 69, 'T'],
            ['M. Zaid U', 'Harry L', '03/11', 'L', '2025-06-20', 9.2, 71, 'T'],
            ['Lanang V.A', 'Joko', '04/11', 'L', '2025-06-28', 3.2, 69, 'T'],
            ['Raisa Amarilia S', 'Aditya R', '03/11', 'P', '2025-06-12', 6.9, 64, 'T'],
            ['Grace Veliora', 'Bachtiar', '02/11', 'P', '2025-03-26', 5, 61, 'T'],
            ['M. Zacky J', 'M. Asysyam', '01/11', 'L', '2025-08-01', 7.5, 66, 'N'],
            ['Athertina A.G', 'Ismu', '04/11', 'P', '2025-08-03', 6.9, 44, 'T'],
            ['Hasan Asyub A', 'M. Endang', '01/11', 'L', '2025-09-11', 5.7, 60, 'N'],
            ['M. Ibrahim R', 'Hardian', '01/11', 'L', '2025-09-27', 6.4, 64, 'N'],
            ['Arcelia A.A', 'Aldifa', '02/11', 'P', '2025-10-07', 4.8, 59, 'T'],
            ['Barrea Ghazala A', 'Ali Baba A', '01/11', 'P', '2022-05-06', 15.5, 104, 'B'],
            ['Khanza Hamdia A', 'M. Bayu', '03/11', 'P', '2025-11-30', 4.4, 54, 'B'],
            ['Faturrasya Shabir', 'Sutono', '02/11', 'L', '2022-10-17', 14.3, 97, 'B'],
            ['Kautsar Arsaka', 'Zakky B', '03/11', 'L', '2025-12-20', 3.1, 51, 'B'],
            ['Sabhira N.R', 'Sutono', '02/11', 'P', '2024-08-02', 9.9, 74, 'B'],
        ];

        // ==========================================
        // 2. DATA FEBRUARI 2026 (5 Februari 2026)
        // ==========================================
        $febDate = '2026-02-05';
        $febData = [
            // [Nama, RT/RW, Ortu, L/P, Tgl Lahir, Umur, BB, TB, Status]
            ['Alfian Zaftan M', '02/11', 'M Asysyam', 'L', '2021-06-02', 60, 21.5, 112, 'TE'],
            ['Vallera Carmen R', '02/11', 'Ragid', 'P', '2021-09-23', 56, 15.8, 98, 'T'],
            ['Salwa D.Z', '02/11', 'Heri', 'P', '2021-06-29', 55, 14.5, 96, 'T'],
            ['Nata Noel', '01/11', 'Marjohan', 'L', '2021-06-21', 53, 12.3, 93, '-'],
            ['Sabhika', '01/11', 'Nardiansyah', 'P', '2021-01-20', 50, 15.7, 98, '-'],
            ['Tsabina A.L', '01/11', 'Astu', 'P', null, 48, 19.6, 102, 'N'],
            ['Ganester', '01/11', 'Adi Rasa', 'L', '2022-06-03', 48, 16.5, 99, 'R'],
            ['Ryuga A.H', '02/11', 'Rahmat', 'L', '2022-05-21', 48, 14.5, 97, 'TE'],
            ['Anindya Dhea', '02/11', 'Asysyam', 'P', '2022-01-20', 49, null, null, 'TE'],
            ['Mikael', '01/11', 'Winson', 'L', '2022-04-03', 46, 14, 96, '7'],
            ['Arka', '02/11', 'Suherman', 'L', '2022-06-21', 45, 12.7, 90, '-'],
            ['Khaizanu', '03/12', 'Heriyawan', 'L', null, 45, 16.6, 96, 'Te'],
            ['Fatimah', '01/11', 'A. Akbar', 'P', '2022-05-20', 45, 13.5, 90, 'TE'],
            ['Arfan Sidqi', '03/11', 'Jadi', 'L', '2022-06-29', 45, 15.3, 100, 'Tt'],
            ['Nabila Warna', '01/11', 'Adri M.S', 'P', '2022-07-03', 43, 11.5, 93, 'T'],
            ['Assela Fida', '04/11', 'Ujang', 'P', '2022-07-07', 43, 13.2, 89, 'Te'],
            ['Mahira N.P.E.A', '02/11', 'Duta A', 'P', '2022-07-13', 43, 16.3, 100, 'TE'],
            ['A. Zafran U.R', '04/11', 'Ryan R.R', 'L', '2022-08-06', 42, 16.3, 99, 'TE'],
            ['Raty Raline VCB', '02/11', 'Bachtiar', 'P', '2022-12-07', 38, 14.1, 94, '-'],
            ['Mikhayla A.F', '03/11', 'Catur', 'P', '2022-02-26', 37, 11.9, 91, 'T'],
            ['Safia', '02/11', 'Wisnu', 'P', '2023-05-01', 36, 7.5, 97, '-'],
            ['Moriel N.P.A', '02/11', 'Nuel K', 'P', '2023-03-25', 35, 10.9, 89, 'TE'],
            ['Albiru', '01/11', 'Ariestya', 'L', '2023-03-25', 35, 12.7, 88, 'To'],
            ['Shaynala', '03/11', 'Syahmi', 'P', '2023-04-10', 34, 12.9, 92, 'TE'],
            ['M. Azzumar', '02/11', 'M. Kamaludin', 'L', '2023-04-16', 34, 11.8, 84, 'TE'],
            ['Adi Tama', '03/11', 'Dodi', 'L', '2023-04-22', 34, 13.7, 93, 'TE'],
            ['Calvin Zane', '04/11', 'Ananda', 'L', '2023-06-23', 33, 19.2, 92, '-'],
            ['Jasmin', '04/11', 'Oji', 'P', '2023-06-04', 32, 12.6, null, 'Th'],
            ['Gladis A', '01/11', 'Adi Rasa', 'P', '2023-06-02', 32, 8.9, 10, 'R'],
            ['M. Ichsan A', '02/11', 'Imam', 'L', '2023-06-26', 32, 13.2, 94, 'V'],
            ['Askara A', '02/11', 'Arindra', 'P', '2023-07-20', 31, 12.1, 87, 'M'],
            ['Azam Rafasya', '01/11', 'Irwansyah', 'L', '2023-08-11', 29, 10.8, 76, 'TE'],
            ['M. Zidan Rafdi', '03/11', 'Rafdi', 'L', '2023-05-10', 30, 13.5, 92, 'TH'],
            ['Faiza Takzia', '01/11', 'Hardian', 'P', '2023-10-15', 28, 12.7, 87, 'Tt'],
            ['Mikail', '02/11', 'Oki', 'L', '2024-04-22', 22, 10.2, 85, 'V'],
            ['Argawira A.A', '02/11', 'Aldifa', 'L', '2024-05-17', 21, 11.8, 82, 'TE'],
            ['M. Albifarzan', '01/11', 'M. Yuda', 'L', '2024-08-06', 19, 9.7, 79, 'T'],
            ['Abhipraya A', '02/11', 'Arief S.S', 'L', '2024-08-20', 18, 10, 79, 'TE'],
            ['M. Maulia R', '03/11', 'Era K', 'L', '2024-09-05', 17, 9.1, 72, 'Th'],
            ['Elisa Shanum', '01/11', 'Al Putra', 'P', '2024-09-23', 17, 9.3, 74, 'M'],
            ['Alena Shalihah', '04/11', 'Ghema P', 'P', '2024-06-03', 16, 8.7, 77, 'Te'],
            ['Haura R.R', '04/11', 'Ridwan', 'P', '2024-10-04', 16, 9.3, 76, 'TE'],
            ['Barra Al Fatih', '01/11', 'Wiwit A', 'L', '2024-10-01', 15, 14.3, 81, 'Tt'],
            ['Soca M.N', '01/11', 'Van B.P', 'P', '2025-03-05', 11, 9.2, 72, 'TE'],
            ['Mistiael V.P.A', '02/11', 'Muel K', 'P', '2025-03-24', 11, 7, 72, '-'],
            ['M. Zaid U', '03/11', 'Harry L', 'L', '2025-06-20', 8, 9.7, 73, '-'],
            ['Raisa Amarilia S', '02/11', 'Bachtiar', 'P', '2025-06-12', 8, 7.1, 65, '-'],
            ['Lanang V.A', '04/11', 'Doko', 'L', '2025-06-28', 8, 8.5, 68, 'TH'],
            ['Grace Veliora', '02/11', 'Bachtiar', 'P', '2025-07-26', 7, 6.1, 65, 'N'],
            ['M. Zacky', '02/11', 'M. Asysyam', 'L', '2025-08-01', 6, 7.8, 70, '2'],
            ['Albertina A', '04/11', 'Ismu', 'P', '2025-08-08', 6, 6.8, 68, 'N'],
            ['Hasan Ayyub A', '03/11', 'M. Endang', 'L', '2025-09-01', 5, 6.2, 60, 'Tt'],
            ['M. Ibrahim R', '01/11', 'Hardian', 'L', '2025-09-27', 5, 7.3, 64, 'N'],
            ['Arcelia A.A', '02/11', 'Aldifa', 'P', '2025-10-07', 4, 6.6, 64, 'N'],
        ];

        // ==========================================
        // 3. DATA MARET 2026 (2 Maret 2026)
        // ==========================================
        $marDate = '2026-03-02';
        $marData = [
            // [Nama, RT/RW, Ortu, L/P, Tgl Lahir, Umur, BB, TB, Status]
            ['Alfian Zaftan M', '02/11', 'M Said', 'L', '2021-06-02', 61, null, null, '-'],
            ['Vallera Carmen R', '03/11', 'Usman', 'P', '2021-09-23', 59, 15.8, 98, 'T'],
            ['Salwa D.Z', '01/11', 'Heri S', 'P', '2021-06-29', 57, 15.3, 108, '-'],
            ['Naha Mael', '01/11', 'Marjahan', 'L', '2021-07-21', 54, null, null, 'TE'],
            ['Sabhika', '01/11', 'Nardiansyah', 'P', '2021-01-20', 50, 16, 100, 'M'],
            ['Tsabina A.L', '03/11', 'Astu', 'P', '2022-03-29', 49, 16.2, 96, 'M'],
            ['Ganesti', '02/11', 'Adi Rasa', 'L', '2022-06-03', 48, 14.7, 96, 'M'],
            ['Ryuga A.H', '02/11', 'Rahmat', 'L', '2022-05-21', 47, 12.6, 95, 'TE'],
            ['Anindya Dhea', '02/11', 'Asysyam', 'P', '2022-01-20', 46, 10.2, null, 'TE'],
            ['Mikael', '01/11', 'Winson', 'L', '2022-04-03', 46, 14.2, 96, 'Tt'],
            ['Arka', '02/11', 'Suherman', 'L', '2022-06-21', 46, 13, 90, '-'],
            ['Khaizanu', '03/11', 'Heriyawan', 'L', '2022-05-24', 46, 16.2, 96, '-'],
            ['Fatimah Yomi', '01/11', 'A. Akteon', 'P', '2022-05-22', 46, 15.8, 105, 'TE'],
            ['Barrea Chazada', '01/11', 'Ali Baba', 'P', '2025-09-06', 46, null, null, '-'],
            ['Arfan Sidqi', '03/11', 'Jodi', 'L', '2022-06-29', 46, 15.6, 100, '-'],
            ['Mabila Warna', '01/11', 'Adri M.S', 'P', '2022-07-03', 44, 11.8, 90, 'Th'],
            ['Assela Fida', '04/11', 'Ujang', 'P', '2022-07-07', 44, 13.5, 94, 'TH'],
            ['Mahira N.P.E.A', '02/11', 'Duta A', 'P', '2022-07-13', 44, 16.5, 100, '-'],
            ['A. Zafran U.R', '09/11', 'Ryan R.R', 'L', '2022-08-16', 43, null, null, '-'],
            ['Raty Raline VCB', '02/11', 'Bachtiar', 'P', '2022-12-07', 39, 14.3, 95, 'R'],
            ['Mikhayla A.F', '03/11', 'Catur', 'P', '2022-02-26', 38, 12.4, 91, 'M'],
            ['Safia', '02/11', 'Wisnu', 'P', '2023-05-01', 37, 17.7, 97, 'TE'],
            ['Moriel N.P.A', '02/11', 'Nuel K', 'P', '2023-03-25', 36, 10.7, 91, '-'],
            ['Albiru', '01/11', 'Ariestya', 'L', '2023-03-25', 36, 12.9, 83, '-'],
            ['Shaynala', '03/11', 'Syahmi', 'P', '2023-04-10', 35, 12.9, 92, 'TR'],
            ['M. Azzumar', '02/11', 'M. Kamaludin', 'L', '2023-04-16', 35, 11, 84, 'TE'],
            ['Adi Tama', '03/11', 'Dodi', 'L', '2023-04-22', 35, 13.8, 92, 'P'],
            ['Calvin Zane', '04/11', 'Ananda', 'L', '2023-05-22', 34, 12.8, 92, 'T'],
            ['Mikail', '02/11', 'Oki', 'L', '2024-04-22', 23, 11.3, 85, 'Tt'],
            ['Argawira A.A', '02/11', 'Aldifa', 'L', '2024-05-17', 22, 11.9, 84, 'TR'],
            ['M. Albifarzan', '01/11', 'M. Yuda', 'L', '2024-08-06', 19, 9.9, 75, 'TE'],
            ['Abhipraya A', '02/11', 'Arief S.S', 'L', '2024-08-20', 19, 10.2, 75, 'Tt'],
            ['M. Maulia R', '03/11', 'Era K', 'L', '2024-09-05', 18, 9.1, 72, 'TE'],
            ['Elisa Shanum', '01/11', 'Al Putra', 'P', '2024-09-23', 18, 9.3, 74, 'TE'],
            ['Alena Shalihah', '04/11', 'Ghema P', 'P', '2024-10-03', 17, 14, 77, 'T'],
            ['Haura R.R', '04/11', 'Ridwan', 'P', '2024-10-04', 17, 8.8, 73, 'Tt'],
            ['Barra Al Fatih', '01/11', 'Wiwit A', 'L', '2024-10-01', 17, 9.3, 81, 'H'],
            ['Soca M.N', '01/11', 'Van B.P', 'P', '2025-03-05', 12, 14.5, 72, 'N'],
            ['Mistiael V.P.A', '02/11', 'Muel K', 'P', '2025-03-24', 12, 9.4, 74, '-'],
            ['M. Zaid U', '03/11', 'Harry L', 'L', '2025-06-20', 9, 9.6, 73, 'T'],
            ['Raisa Amarilia', '02/11', 'Aditya R', 'P', '2025-06-12', 9, 7.5, 66, 'N'],
            ['Lanang V.A', '04/11', 'Joko', 'L', '2025-06-28', 9, null, 70, 'Tt'],
            ['Grace Veliora', '02/11', 'Bachtiar', 'P', '2025-07-26', 8, 6.5, 65, '-'],
            ['M. Zacky', '02/11', 'M. Asysyam', 'L', '2025-08-01', 7, 8.4, 68, 'Tt'],
        ];

        // ==========================================
        // 4. DATA APRIL 2026 (1 April 2026)
        // ==========================================
        $aprDate = '2026-04-01';
        $aprData = [
            // [Nama Balita, Nama Ortu, RT/RW, L/P, Tanggal Lahir, BB, TB, Indikator, Ket]
            ['M. Said', 'Ragil', '03/11', 'L', '2021-04-20', 16, 98, 'N', ''],
            ['Vallera Darren', 'Usman', '02/11', 'L', '2021-04-23', 15.5, 108, 'T', ''],
            ['Salwa D. 2', 'Heri S', '01/11', 'P', '2021-06-29', 32.7, 111, 'T', ''],
            ['Nata Noel', 'Marjohan', '01/11', 'L', '2021-06-21', 19, 93, 'N', ''],
            ['Sabrina', 'Murdiansyah', '01/11', 'P', '2021-09-29', 16.8, 97, 'T', ''],
            ['Tsabina A.L', 'Asty K.2', '03/11', 'P', '2021-06-20', 15.2, 97, 'N', ''],
            ['Ganesh M.D', 'Adi Rasa', '01/11', 'L', '2022-01-06', 12.8, 95, 'T', ''],
            ['Ryuga A.H', 'Rahmat T', '02/11', 'L', '2022-02-03', 16, 102, 'N', ''],
            ['Anindya Dhea', 'M. Asysyam', '02/11', 'P', '2022-02-28', 15.7, 100, 'T', ''],
            ['Mikael', 'Winson', '01/11', 'L', '2022-04-03', 14.5, 99, 'T', ''],
            ['Arka R', 'Suherman', '03/11', 'L', '2022-05-21', 13.3, 92, 'N', ''],
            ['Khaizanu', 'Heniyawan', '03/11', 'L', '2022-06-21', 17.8, 98, 'N', ''],
            ['Fatimah Yumi', 'A. Akbar', '01/11', 'L', '2022-05-20', null, null, null, 'Pindah'],
            ['Barrea Ghazala', 'Ali Baba', '01/11', 'P', '2022-05-06', 16, 105, 'N', ''],
            ['Arfan Sidqi', 'Jodi', '01/11', 'L', '2022-05-29', 11, 100, 'N', ''],
            ['Nabila Warna', 'Adri M.S', '01/11', 'P', '2022-04-03', 11.9, 90, 'T', ''],
            ['Assela Fida', 'Ujang', '04/11', 'P', '2022-04-07', 13.8, 95, 'T', ''],
            ['A. Zafran U.R', 'Riban R.R', '01/11', 'L', '2022-06-06', 16.7, 101, 'T', ''],
            ['Ratu Raline VCB', 'Bachtiar', '02/11', 'P', '2022-02-01', 14.3, 95, 'T', ''],
            ['Michayla A.F', 'Datvr', '03/11', 'P', '2022-06-26', 12.1, 91, 'T', ''],
            ['Safia', 'Wisnu', '03/10', 'L', '2023-01-05', 18, 97, 'N', ''],
            ['Moriel N.P.A', 'Miel K', '03/11', 'L', '2023-03-25', 11.3, 93, 'T', ''],
            ['Albira', 'Anestya', '01/11', 'P', '2023-03-02', 13, 93, 'N', ''],
            ['Shaynala', 'Siahmi', '03/11', 'P', '2023-04-10', 13.3, 92, 'N', ''],
            ['M. Azzumar', 'M. Kamaludin', '02/11', 'L', '2023-04-16', 11.1, 84, 'T', ''],
            ['Adi Tama', 'Dodi F', '03/11', 'L', '2023-04-22', 13.5, 92, 'T', ''],
            ['Calvin Zane', 'Ananda', '04/11', 'L', '2023-05-22', 13.1, 92, 'N', ''],
            ['Jasmin', 'Oji', '04/11', 'P', '2023-06-04', 12.8, 78, 'T', ''],
            ['Gladis A', 'Adi Rasa', '01/11', 'P', '2023-06-21', 10.6, 86, 'T', ''],
            ['M. Ichsan A', 'Imam', '02/11', 'L', '2023-06-26', 12.7, 96, 'T', ''],
            ['Askara A', 'Arindra', '07/11', 'P', '2023-04-20', null, null, null, 'Pindah'],
            ['Azzam Rafasya', 'Irwansyah', '01/11', 'L', '2023-09-11', 11, 84, 'N', ''],
            ['M. Zidan A', 'Rapdi H', '03/11', 'L', '2023-09-10', 14.3, 87, 'T', ''],
            ['Faiza Takzia', 'Hardian', '01/11', 'P', '2023-10-15', 12.3, 89, 'T', ''],
            ['Annisa Jafran', 'Riangga', '01/11', 'P', '2023-11-09', 11.6, 85, 'T', ''],
            ['Abraham', 'Riswan', '02/11', 'L', '2024-05-20', 12.5, 89, 'T', ''],
            ['Ashraf Faisan', 'Alan F', '02/11', 'L', '2024-05-20', 11.1, 82, 'N', ''],
            ['Cerenah U.T', 'Theresa', '04/11', 'P', '2024-06-20', 12.5, 74, 'T', ''],
            ['Kirei  Hafza H', 'Rahmat T', '02/11', 'P', '2024-03-27', 14, 92, 'N', ''],
            ['Mikail', 'Oki', '02/11', 'P', '2024-04-22', 11.5, 85, 'T', ''],
        ];

        // Seed sheet data Januari
        $this->seedJanuariData($janDate, $janData, $posyanduId, $calculator);

        // Seed sheet data Februari
        $this->seedSheetData($febDate, $febData, $posyanduId, $calculator);

        // Seed sheet data Maret
        $this->seedSheetData($marDate, $marData, $posyanduId, $calculator);

        // Seed sheet data April
        $this->seedAprilData($aprDate, $aprData, $posyanduId, $calculator);
    }

    private function seedJanuariData(string $visitDate, array $dataset, int $posyanduId, NutritionCalculatorService $calculator)
    {
        $carbonVisitDate = Carbon::parse($visitDate);
        foreach ($dataset as $item) {
            $name = $item[0];
            $ortu = $item[1];
            $rtRw = $item[2];
            $gender = $item[3] === 'L' ? 'L' : 'P';
            $birthDate = $item[4];
            $weight = $item[5];
            $height = $item[6];
            $weightStatus = $item[7];

            $patient = Patient::where('full_name', 'like', $name)->first();
            if (!$patient) {
                $patient = Patient::create([
                    'full_name' => $name,
                    'category' => 'balita',
                    'gender' => $gender,
                    'birth_date' => $birthDate,
                    'mother_name' => $ortu,
                    'dusun_rt_rw' => $rtRw,
                    'weight_at_birth' => 3.0,
                    'height_at_birth' => 50.0,
                    'status_mutasi' => 'aktif',
                    'posyandu_id' => $posyanduId,
                    'id_number' => \Faker\Factory::create('id_ID')->unique()->numerify('################'),
                    'address' => 'RT/RW ' . $rtRw
                ]);
            }

            $actualBirthDate = Carbon::parse($patient->birth_date);
            $actualAgeMonths = max(0, (int) $actualBirthDate->diffInMonths($carbonVisitDate));

            $nutritionData = $this->calculateNutrition($weight, $height, $actualAgeMonths, $patient->gender, $calculator);
            $this->createMedicalRecord($patient->id, $visitDate, $weight, $height, $weightStatus, $nutritionData, $actualAgeMonths);
        }
    }

    private function seedSheetData(string $visitDate, array $dataset, int $posyanduId, NutritionCalculatorService $calculator)
    {
        $carbonVisitDate = Carbon::parse($visitDate);
        foreach ($dataset as $item) {
            $name = $item[0];
            $rtRw = $item[1];
            $ortu = $item[2];
            $gender = $item[3] === 'L' ? 'L' : 'P';
            $birthDate = $item[4];
            $ageMonths = $item[5];
            $weight = $item[6];
            $height = $item[7];
            $weightStatus = $item[8];

            $patient = Patient::where('full_name', 'like', $name)->first();
            if (!$patient) {
                if (!$birthDate) {
                    $birthDate = $carbonVisitDate->copy()->subMonths($ageMonths)->format('Y-m-d');
                }
                $patient = Patient::create([
                    'full_name' => $name,
                    'category' => 'balita',
                    'gender' => $gender,
                    'birth_date' => $birthDate,
                    'mother_name' => $ortu,
                    'dusun_rt_rw' => $rtRw,
                    'weight_at_birth' => 3.0,
                    'height_at_birth' => 50.0,
                    'status_mutasi' => 'aktif',
                    'posyandu_id' => $posyanduId,
                    'id_number' => \Faker\Factory::create('id_ID')->unique()->numerify('################'),
                    'address' => 'RT/RW ' . $rtRw
                ]);
            } else {
                if ($birthDate) {
                    $patient->update(['birth_date' => $birthDate]);
                }
                if ($ortu && !$patient->mother_name) {
                    $patient->update(['mother_name' => $ortu]);
                }
            }

            $actualBirthDate = Carbon::parse($patient->birth_date);
            $actualAgeMonths = max(0, (int) $actualBirthDate->diffInMonths($carbonVisitDate));

            $nutritionData = $this->calculateNutrition($weight, $height, $actualAgeMonths, $patient->gender, $calculator);
            $this->createMedicalRecord($patient->id, $visitDate, $weight, $height, $weightStatus, $nutritionData, $actualAgeMonths);
        }
    }

    private function seedAprilData(string $visitDate, array $dataset, int $posyanduId, NutritionCalculatorService $calculator)
    {
        $carbonVisitDate = Carbon::parse($visitDate);
        foreach ($dataset as $item) {
            $name = $item[0];
            $ortu = $item[1];
            $rtRw = $item[2];
            $gender = $item[3] === 'L' ? 'L' : 'P';
            $birthDate = $item[4];
            $weight = $item[5];
            $height = $item[6];
            $weightStatus = $item[7];
            $statusMutasi = ($item[8] === 'Pindah') ? 'pindah' : 'aktif';

            $patient = Patient::where('full_name', 'like', $name)->first();
            if (!$patient) {
                $patient = Patient::create([
                    'full_name' => $name,
                    'category' => 'balita',
                    'gender' => $gender,
                    'birth_date' => $birthDate,
                    'mother_name' => $ortu,
                    'dusun_rt_rw' => $rtRw,
                    'weight_at_birth' => 3.0,
                    'height_at_birth' => 50.0,
                    'status_mutasi' => $statusMutasi,
                    'posyandu_id' => $posyanduId,
                    'id_number' => \Faker\Factory::create('id_ID')->unique()->numerify('################'),
                    'address' => 'RT/RW ' . $rtRw
                ]);
            } else {
                $patient->update(['status_mutasi' => $statusMutasi]);
            }

            if ($statusMutasi !== 'pindah') {
                $actualBirthDate = Carbon::parse($patient->birth_date);
                $actualAgeMonths = max(0, (int) $actualBirthDate->diffInMonths($carbonVisitDate));

                $nutritionData = $this->calculateNutrition($weight, $height, $actualAgeMonths, $patient->gender, $calculator);
                $this->createMedicalRecord($patient->id, $visitDate, $weight, $height, $weightStatus, $nutritionData, $actualAgeMonths);
            }
        }
    }

    private function calculateNutrition($weight, $height, $ageMonths, $gender, NutritionCalculatorService $calculator): array
    {
        $nutritionData = [
            'nutrition_status' => 'Gizi Baik',
            'z_score' => null,
            'stunting_status' => 'Normal',
            'z_score_hfa' => null,
            'wasting_status' => 'Gizi Baik',
            'z_score_wfh' => null,
            'z_score_bfa' => null,
        ];

        if ($weight > 0) {
            $calcHeight = $height > 0 ? $height : 0;
            $result = $calculator->calculateAll($weight, $calcHeight, $ageMonths, $gender);
            
            $nutritionData = [
                'nutrition_status' => $result->nutrition_status,
                'z_score' => $result->z_score,
                'stunting_status' => $result->stunting_status,
                'z_score_hfa' => $result->z_score_hfa,
                'wasting_status' => $result->wasting_status,
                'z_score_wfh' => $result->z_score_wfh,
                'z_score_bfa' => $result->z_score_bfa,
            ];
        }

        return $nutritionData;
    }

    private function createMedicalRecord($patientId, $visitDate, $weight, $height, $weightStatus, array $nutritionData, $ageMonths)
    {
        if ($weight > 0) {
            // Generate random imunisasi yang realistis agar grafik tidak kosong
            $schedule = [
                0 => ['HB-0', 'Polio 0'],
                1 => ['BCG', 'Polio 1'],
                2 => ['DPT-HB-Hib 1', 'Polio 2', 'PCV 1', 'RV 1'],
                3 => ['DPT-HB-Hib 2', 'Polio 3', 'PCV 2', 'RV 2'],
                4 => ['DPT-HB-Hib 3', 'Polio 4', 'IPV 1', 'RV 3'],
                9 => ['MR', 'IPV 2'],
                12 => ['PCV 3'],
                18 => ['DPT-HB-Hib Lanjutan', 'MR Lanjutan'],
            ];

            $received = [];
            foreach ($schedule as $age => $vaxs) {
                if ($ageMonths >= $age) {
                    foreach ($vaxs as $vax) {
                        // 85% probability
                        if (rand(1, 100) <= 85) {
                            $received[] = $vax;
                        }
                    }
                }
            }
            $vaxString = count($received) > 0 ? implode(', ', $received) : null;

            MedicalRecord::updateOrCreate(
                [
                    'patient_id' => $patientId,
                    'visit_date' => $visitDate,
                ],
                array_merge([
                    'weight' => $weight,
                    'height' => (float)($height ?? 0),
                    'weight_status' => $weightStatus,
                    'vaccine_name' => $vaxString,
                    'immunization' => $vaxString,
                    'complaint' => '-',
                    'health_note' => '-',
                    'diagnosis' => '-',
                    'user_id' => 1
                ], $nutritionData)
            );
        }
    }
}
