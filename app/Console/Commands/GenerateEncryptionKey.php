<?php

namespace App\Console\Commands;

use Defuse\Crypto\Key;
use Illuminate\Console\Command;

class GenerateEncryptionKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posyandu:generate-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new encryption key for Defuse/php-encryption';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $key = Key::createNewRandomKey();
        $asciiString = $key->saveToAsciiSafeString();

        $this->info('Kunci Enkripsi Berhasil Dibuat:');
        $this->line($asciiString);
        $this->newLine();
        $this->warn('Simpan kunci ini di file .env Anda sebagai ENCRYPTION_KEY');
        $this->comment('Contoh: ENCRYPTION_KEY='.$asciiString);

        return Command::SUCCESS;
    }
}
