<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanPublicStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clean-public';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina todos los archivos dentro de storage/app/public';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = storage_path('app/public');


        if (!File::exists($path)) {
            $this->warn('No existe la carpeta storage/app/public');
            return;
        }

        // Eliminar archivos excepto .gitignore
        foreach (File::files($path) as $file) {
            if ($file->getFilename() !== '.gitignore') {
                File::delete($file->getRealPath());
            }
        }

        // Eliminar subcarpetas
        foreach (File::directories($path) as $dir) {
            File::deleteDirectory($dir);
        }

        $this->info('Archivos eliminados, .gitignore preservado.');
    }
}
