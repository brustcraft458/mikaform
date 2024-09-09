<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup the database and upload to Google Drive';
    protected $ds = DIRECTORY_SEPARATOR;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Init
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');

        // Temp Upload Dir
        $storagePath = storage_path('app' . $this->ds . 'sqlbackup');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Filename
        $fileName = 'backup_' . date('Y_m_d_His') . '.sql';
        $filePath = $storagePath . $this->ds . $fileName;

        // Dump SQL DB
        $dumpCommand = 'mysqldump --user=' . $dbUser . ' --password=' . $dbPassword . ' --host=' . $dbHost . ' ' . $dbName . ' > ' . '"' . $filePath . '"';
        system($dumpCommand);

        // Upload ile Google Drive
        if (file_exists($filePath)) {
            $this->uploadToGoogleDrive($filePath, $fileName);
            unlink($filePath); // Delete tmp
            $this->info('Database backup uploaded to Google Drive successfully.');
        } else {
            $this->error('Failed to create database backup.');
        }
    }

    private function uploadToGoogleDrive($filePath, $fileName)
    {
        // Init
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app' . $this->ds .'gdrive_credentials.json'));
        $client->addScope(Google_Service_Drive::DRIVE_FILE);

        $service = new Google_Service_Drive($client);

        $file = new Google_Service_Drive_DriveFile();
        $file->setName($fileName);

        // Set folder uploaded
        $folderId = env('GOOGLE_DRIVE_FOLDER_ID');
        if ($folderId) {
            $file->setParents([$folderId]);
        }

        // Temp file content
        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \Exception("Failed to read file content from: $filePath");
        }

        // Create file Google Drive
        $createdFile = $service->files->create($file, [
            'data' => $content,
            'mimeType' => mime_content_type($filePath),
            'uploadType' => 'multipart'
        ]);

        return $createdFile;
    }

}
