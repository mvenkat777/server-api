<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\App\Wrappers\AwsS3Wrapper;
use Platform\App\Wrappers\PdfGenerator;
use Platform\Techpacks\Commands\FormatTechpackForExportCommand;
use Platform\Techpacks\Mailer\TechpackMailer;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Platform\Uploads\Helpers\UploadHelpers;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GenerateTechpackVendorExportCommandHandler implements CommandHandler
{
    /**
     * @var Platform\App\Wrappers\PdfGenerator
     */
    protected $export;

    /**
     * @var Platform\Techpacks\Repositories\Contracts\TechpackRepository
     */
    protected $techpack;

    /**
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * @var TechpackMailer
     */
    protected $mailer;

    /**
     * @var AwsS3Wrapper
     */
    protected $aws;

    /**
     * @param PdfGenerator       $pdf
     * @param TechpackRepository $techpack
     * @param DefaultCommandBus  $commandBus
     * @param TechpackMailer     $mailer
     * @param AwsS3Wrapper       $aws
     */
    public function __construct(
        PdfGenerator $pdf,
        TechpackRepository $techpack,
        DefaultCommandBus $commandBus,
        TechpackMailer $mailer,
        AwsS3Wrapper $aws
    ) {
        $this->export = $pdf;
        $this->techpack = $techpack;
        $this->commandBus = $commandBus;
        $this->mailer = $mailer;
        $this->aws = $aws;
    }

    /**
     * Handler function
     * @param  GenerateTechpackVendorExportCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        $techpackId = $command->techpackId;
        $email = $command->email;
        $selectedFields = $this->getSelectedFields($command->selectedFields);

        $techpack = \App\Techpack::with(['owner', 'cutTickets', 'cutTicketNote'])
                                   ->find($techpackId);
        
        if ($techpack) {
            $techpack = $this->commandBus->execute(new FormatTechpackForExportCommand($techpack));
            $exportName = $this->getExportName($techpack);
            $exportPath = storage_path() . '/' . $exportName . '.pdf';
            if (file_exists($exportPath)) {
                \File::delete($exportPath);
            }

            $this->export->setPaper('A4', 'landscape');
            $this->export->setOption('header-spacing', '4');
            $this->export->setOption('footer-spacing', '3');
            $this->export->setOption('viewport-size', '1024x768');
            $this->export->setHeaderView(
                'exports.techpacks.vendor.header',
                [
                    'techpack' => $techpack->toArray(),
                ]
            );
            $this->export->setFooterView('exports.techpacks.vendor.footer');
            $this->export->loadView(
                'exports.techpacks.vendor.body',
                [
                    'techpack' => $techpack->toArray(),
                    'selectedFields' => $selectedFields,
                ]
            );

            $this->export->save($exportPath);

            if ($command->multipleExport) {
                return $exportPath;
            }

            if ($command->isEmail) {
               $this->mailer->techpackExport(
                    \Auth::user(),
                    [$exportPath],
                    []
                );
               \File::delete($exportPath);
               return "Email option was chosen";
            }
            try {
                $uploadedFile = $this->aws->uploadFromPath($exportPath);
            } catch (\Exception $e) {
                \File::delete($exportPath);
                throw new SeException(
                    "Download option does not seem to work right now. Please try to email.",
                    404
                );
            }

            if ($uploadedFile) {
                \File::delete($exportPath);
                return $uploadedFile['ObjectURL'];
            }
            return false;
        }

        throw new SeException('Techpack with that id not found.', 404);

    }

    /**
     * Get selected fields for techpack selective export
     * @param  array $selectedFields
     * @return array
     */
    public function getSelectedFields($selectedFields)
    {
        $defaultFields = [
            "billOfMaterials" => true,
            "poms" => true,
            "gradedSpecSheet" => true,
            "cutTicket" => true,
            "documents" => [
                "photo" => true,
                "construction" => true,
                "cad" => true,
                "howToMeasure" => true,
                "others" => true,
            ]
        ];

        return array_merge($defaultFields, $selectedFields);
    }

    /**
     * Return the string which will be used for exporting techpack name
     *
     * @var object $techpack
     * @return string
     */
    private function getExportName($techpack)
    {
        return $this->restrictToFiftyChar($techpack->name) . '_' . $this->getCustomerOrStyleCode($techpack) . '_' . $this->getTimestamp();
    }

    /**
     * Get style code if it has customer code, else return both customer name and style code
     *
     * @var object $techpack
     * @return string
     */
    private function getCustomerOrStyleCode($techpack)
    {
        if($this->hasCustomerCode($techpack)) {
            return $techpack->style_code;
        }
        return $techpack->meta->customer->name . '_' . $techpack->style_code;
    }

    /**
     * Restrict string to 50 characters
     *
     * @var string $name
     * @return string
     */
    private function restrictToFiftyChar($name)
    {
        if(strlen($name) > 50) {
            return substr($name, 50);
        }

        return $name;
    }

    /**
     * Check if style code has customer code or not
     *
     * @var object $techpack
     * @return boolean
     */
    private function hasCustomerCode($techpack)
    {
        if(isset($techpack->meta->customer->code) && $this->containsCode($techpack->meta->customer->code, $techpack->style_code)){
            return true;
        }
        if(!is_null($techpack->customer) && $this->containsCode($techpack->customer->code, $techpack->style_code)) {
            return true;
        }
        return false;
    }

    /**
     * Check if subsrting present in the main string
     *
     * @var string $subStr
     * @var string $string
     * @return 0/1
     */
    private function containsCode($subStr, $string)
    {
        return preg_match("/$subStr/", $string); // Because it always return 0 or 1
    }

    /**
     * Get current Timestame with date format
     *
     * @var string $format
     * @return date
     */
    private function getTimestamp($format = 'dMy')
    {
        return date($format);
    }
}
