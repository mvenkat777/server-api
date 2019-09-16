<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Techpacks\Commands\GenerateTechpackVendorExportCommand;
use Platform\Techpacks\Mailer\TechpackMailer;
use app\User;

class MultipleTechpackExportCommandHandler implements CommandHandler
{
    private $commandBus;
    private $techpackMailer;

    public function __construct(
        DefaultCommandBus $commandBus,
        TechpackMailer $techpackMailer
    ) {
        $this->commandBus = $commandBus;
        $this->techpackMailer = $techpackMailer;
    }

	public function handle($command)
	{
        $techpackIds = $command->techpackIds;
        $exports = [];

        foreach ($techpackIds as $techpackId) {
            $exports[] = $this->commandBus->execute(
                new GenerateTechpackVendorExportCommand(
                    $command->data,
                    $techpackId,
                    [],
                    true
                )
            );
        }

        $compressedExportsPath = storage_path() . '/' . $this->getExportName(count($techpackIds));
        $compressed = $this->compressFiles($exports, $compressedExportsPath);

        $this->techpackMailer->techpackMultipleExport(
            User::where('email',$command->data['email'])->first(),
            [$compressedExportsPath]
        );
        return true;
	}

    /**
     * Get techpack export name
     * @param  integer $numberOfTechpacks
     * @return string
     */
    public function getExportName($numberOfTechpacks)
    {
        return 'se_techpacks_' . $numberOfTechpacks . '_' . date('m-d-Y H:i:s') . '.zip';
    }

    function compressFiles($files = [], $destination = '') {
        if(file_exists($destination)) {
            \File::delete($destination);
        }
        $validFiles = [];
        if(is_array($files)) {
            foreach($files as $file) {
                if(file_exists($file)) {
                    $validFiles[] = $file;
                }
            }
        }

        if(count($validFiles)) {
            $zip = new \ZipArchive;
            if($zip->open($destination, \ZipArchive::CREATE)) {
                foreach($validFiles as $file) {
                    $zip->addFile($file, pathinfo($file, PATHINFO_BASENAME));
                }
                $zip->close();

                return file_exists($destination);
            }
            return false;

        } else {
            return false;
        }
    }

}