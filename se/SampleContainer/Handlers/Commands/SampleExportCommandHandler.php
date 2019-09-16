<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\App\Wrappers\AwsS3Wrapper;
use Platform\App\Wrappers\PdfGenerator;
use Platform\SampleContainer\Commands\TransformSampleForExportCommand;
use Platform\SampleContainer\Mailer\SampleMailer;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;

class SampleExportCommandHandler implements CommandHandler
{
    /**
     * @var SampleRepository
     */
    private $sample;

    /**
     * @var PdfGenerator
     */
    private $pdf;

    /**
     * @var AwsS3Wrapper
     */
    private $aws;

    /**
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * @var DefaultCommandBus
     */
    private $mailer;

    /**
     * @param SampleRepository $sample
     */
    public function __construct(
        SampleRepository $sample,
        PdfGenerator $pdf,
        AwsS3Wrapper $aws,
        DefaultCommandBus $commandBus,
        SampleMailer $mailer
    ) {
        $this->sample = $sample;
        $this->export = $pdf;
        $this->aws = $aws;
        $this->commandBus = $commandBus;
        $this->mailer = $mailer;
	}

    /**
     * @param SampleExportCommand $command
     */
	public function handle($command)
	{

        $sampleId = $command->sampleId;
        $sample = $this->sample->getByIdWithRelations($sampleId);
        $sample = $this->commandBus->execute(new TransformSampleForExportCommand($sample));
        $exportName = $this->getExportName($sample);
        $exportPath = storage_path() . '/' . $exportName . '.pdf';

        //$file = $this->generatePdf($sample, $exportName, $exportPath);

        $this->export->setPaper('A4', 'landscape');
        $this->export->setOption('header-spacing', '3');
        $this->export->setOption('footer-spacing', '3');
        $this->export->setHeaderView(
            'exports.samples.header',
            [
                'sample' => $sample,
            ]
        );
        $this->export->setFooterView('exports.samples.footer');
        $this->export->loadView(
            'exports.samples.body',
            [
                'sample' => $sample,
            ]
        );

        $uploadedFile = false;
         try {
            $this->export->save($exportPath);
            $uploadedFile = $this->aws->uploadFromPath($exportPath);
         } catch (\Exception $e) {
            if(file_exists($exportPath)) {
                $uploadedFile = $this->aws->uploadFromPath($exportPath);
            } else {
                //\File::delete($exportPath);
                throw new SeException(
                    "Failed to download - please contact platform support at helpdesk@sourceeasy.com",
                    404
                );
            }
        }



        if ($uploadedFile) {
            if ($command->isEmail) {
               $this->mailer->sampleExport(
                    \Auth::user(),
                    [$exportPath],
                    []
                );
               \File::delete($exportPath);
               return "Email option was chosen";
            }
            \File::delete($exportPath);
            return $uploadedFile['ObjectURL'];
        }

        return false;
	}

    /**
     * Return the string which will be used as the sample export filename
     *
     * @var object $sample
     * @return string
     */
    private function getExportName($sample)
    {
        return $this->restrictToFiftyChar($sample->title) . '_' . $this->getTimestamp();
    }

    public function generatePdf($sample, $exportName, $exportPath)
    {
        if (file_exists($exportPath)) {
            \File::delete($exportPath);
        }

        $this->export->setPaper('A4', 'landscape');
        $this->export->setOption('header-spacing', '3');
        $this->export->setOption('footer-spacing', '3');
        $this->export->setOption('load-media-error-handling', 'skip');
        $this->export->setHeaderView(
            'exports.samples.header',
            [
                'sample' => $sample,
            ]
        );
        $this->export->setFooterView('exports.samples.footer');
        try {
            $this->export->loadView(
                'exports.samples.body',
                [
                    'sample' => $sample,
                ]
            );

            return $this->export->save($exportPath);

        } catch (Exception $e) {
            return true;
        }

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
