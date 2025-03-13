<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Data;
use App\Models\Dump;
use App\Models\Section;
use App\Models\Template;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FormDataImport;

class GenerateFormData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:formdata {--import=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dummy form data manually or import from an Excel file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $importFile = $this->option('import');

        if ($importFile) {
            return $this->importFromExcel($importFile);
        }

        return $this->generateManualData();
    }

    /**
     * Method untuk generate data secara manual
     */
    private function generateManualData()
    {
        $this->info("Generating form data manually...");
        $this->info("Bruh");
    }

    /**
     * Method untuk import data dari Excel
     */
    private function importFromExcel($filePath)
    {
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return;
        }

        $this->info("Importing data from: {$filePath}");

        Excel::import(new FormDataImport, $filePath);

        $this->info("Data successfully imported!");
    }
}
