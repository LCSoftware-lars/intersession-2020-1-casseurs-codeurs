<?php

namespace App\Jobs;

use App\Mail\Analyse;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\AnalyseTrait;
use Illuminate\Support\Facades\Mail;

class ProcessSecurity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use AnalyseTrait;
    /**
     * @var string
     */
    private $list;
    /**
     * @var string
     */
    private $baseUrl;
    /**
     * @var string
     */
    private $githubInfo;
    /**
     * @var string
     */
    private $email;
    /**
     * @var int
     */
    private $migration;
    /**
     * @var int
     */
    private $userConnected;

    /**
     * ProcessSecurity constructor.
     * @param string $list
     * @param string $baseUrl
     * @param string $githubInfo
     * @param string $email
     * @param int $migration
     * @param int $userConnected
     */
    public function __construct( string $list, string $baseUrl, string $githubInfo, string $email, int $migration, int $userConnected)
    {
        $this->list = $list;
        $this->baseUrl = $baseUrl;
        $this->githubInfo = $githubInfo;
        $this->email = $email;
        $this->migration = $migration;
        $this->userConnected = $userConnected;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //____________________Copie en local des fichiers php cibles________________________////
        $list = json_decode($this->list);
        // On récupère les chemin des fichiers php du repos:
        $paths = [];
        foreach ($list->tree as $tree){
            if (substr($tree->path, -4) == '.php'){
                $paths[] = $tree->path;
            }
        }
        $baseContentUrl = $this->baseUrl . "contents/";
        foreach ($paths as $path){
            //$baseContentUrl . path pour récupérer le contenu du fichier:
            $content = base64_decode($this->getGithubContent($baseContentUrl.$path)->content);
            $this->addFile($path, $content, base_path().'/public/Scan/');
        }
        $files = [];
        if ($this->migration == 0){
            //____________________Analyse PHPStan________________________////
            $this->analyse('phpstan', $this->githubInfo);
            $files[] = base_path().'/public/reports/'.$this->githubInfo.'_phpstan.json';
            //____________________Analyse Progpilot________________________////
            $this->analyse('progpilot', $this->githubInfo);
            $files[] = base_path().'/public/reports/'.$this->githubInfo.'_progpilot.json';
        }
        elseif ($this->migration == 1 && $this->userConnected){
            //____________________Analyse PHPStan________________________////
            $this->analyse('phpstan', $this->githubInfo);
            $files[] = base_path().'/public/reports/'.$this->githubInfo.'_phpstan.json';
            //____________________Analyse PHP7mar________________________////
            $this->analyse('php7mar', $this->githubInfo);
            $files[] = base_path().'/public/reports/php7mar.md';
            //____________________Analyse Progpilot________________________////
            $this->analyse('progpilot', $this->githubInfo);
            $files[] = base_path().'/public/reports/'.$this->githubInfo.'_progpilot.json';
        }
        elseif ($this->migration == 2 && $this->userConnected){
            //____________________Analyse PHP7mar________________________////
            $this->analyse('php7mar', $this->githubInfo);
            $files[] = base_path().'/public/reports/php7mar.md';
        }

        //Envoie du resultat par mail:
        $project = str_replace("_","/","$this->githubInfo");
        Mail::to($this->email)->send(new Analyse($files, $project));

        if ($this->userConnected){
            $user = User::find($this->userConnected);
            // Conserver les fichiers pour les utilisateur connectés:
            $newFiles = [];
            foreach ($files as $file){
                $newFilename = str_replace('reports/', 'user/'.$user->name.'_', $file);
                rename($file, $newFilename);
                $newFiles[] = $newFilename;
            }
            // Compter le nombre de fichier scanneés:
            $path = base_path().'\public\Scan';
            $objects = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            $count=iterator_count($objects);
            $scannedFiles = $count-2;
            // Associer l'analyse à l'utilisateur dans la base de donnée:
            $analyse = \App\Analyse::where('repository', '=', str_replace("_","/","$this->githubInfo")->first());
            // Si le repos à déjà été scanné:
            if ($analyse != null){
                $analyse->errorsFound = 0;
                $analyse->scannedFiles = $scannedFiles;
                $analyse->numberOfScans = $analyse->numberOfScans+1;
                $analyse->files = json_encode($newFiles);
                $analyse->save();
            }
            else{
                $analyse = new \App\Analyse();
                $analyse->repository = str_replace("_","/","$this->githubInfo");
                $analyse->errorsFound = 0;
                $analyse->scannedFiles = $scannedFiles;
                $analyse->numberOfScans = 1;
                $analyse->files = json_encode($newFiles);

                $user->analyses()->save($analyse);
            }
        }
        else{
            //Suppression des fichiers:
            // reports:
            $dir = base_path().'\public\reports';
            $di = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
            $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ( $ri as $file ) {
                $file->isDir() ?  rmdir($file) : unlink($file);
            }
        }
        //Suppression des fichiers:
        // Scan:
        $dir = base_path()."\public\Scan";
        $di = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
        $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ( $ri as $file ) {
            $file->isDir() ?  rmdir($file) : unlink($file);
        }
    }

}
