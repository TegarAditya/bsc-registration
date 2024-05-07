<?php

namespace App\Filament\Participant\Widgets;

use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Widgets\Widget;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadCertificateWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected static string $view = 'filament.participant.widgets.download-certificate-widget';

    protected static string $title = 'Download Sertifikat Peserta';

    protected static string $documentTitle = 'Sertifikat Peserta BUPIN Science Competition 2024';

    public function getTitle(): string
    {
        return static::$title;
    }

    public function getUserStatus(): bool
    {
        $data = Auth::user()->userDetail;

        if ($data == null) {
            return false;
        }

        return true;
    }

    public function getUserName(): string
    {
        return Auth::user()->name;
    }

    public function getUserCategory(): string
    {
        $category = Auth::user()->userDetail->type;

        switch ($category) {
            case 'KSN':
                return 'Umum';
            case 'KSM':
                return 'Madrasah';
            default:
                return '';
        }
    }

    public function getUserLevel(): string
    {
        $level = Auth::user()->userDetail->grade;

        if (in_array($level, ['SD', 'MI'])) {
            return 'LEVEL 1';
        }

        if (in_array($level, ['SMP', 'MTs'])) {
            return 'LEVEL 2';
        }

        if (in_array($level, ['SMA', 'MA'])) {
            return 'LEVEL 3';
        }

        return '';
    }

    public function getCertificateImage(): string
    {
        switch ($this->getUserCategory()) {
            case "Umum":
                return $this->encodeImage('assets/images/certificates-ksn.jpg');
            case "Madrasah":
                return $this->encodeImage('assets/images/certificates-ksm.jpg');
            default:
                return $this->encodeImage('assets/images/certificates.jpg');
        }
    }

    public function downloadCertificate(): StreamedResponse
    {
        return response()->streamDownload(function () {
            echo Pdf::loadView('pdf.certificate', [
                'name' => $this->getUserName(),
                'certificatePath' => $this->getCertificateImage(),
                'category' => $this->getUserCategory(),
                'level' => $this->getUserLevel(),
                'documentTitle' => static::$documentTitle
            ])
                ->setOption(['defaultFont' => 'sans-serif', 'enable_font_subsetting' => true])
                ->setPaper('a4', 'landscape')
                ->stream();
        }, $this->getUserName() . ' - BSC 2024.pdf');
    }

    /**
     * --------------------------------------------------------------
     * CODE BELOW IS USED TO ENCODE THE IMAGE AS BASE64 STRING
     * --------------------------------------------------------------
     */

    /**
     * Encodes the image located at the given path as a base64 string.
     *
     * @param string $imagePath The path to the image file inside the public directory.
     * @return string The base64 encoded image string.
     */
    public function encodeImage(string $imagePath): string
    {
        return 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path($imagePath)));
    }
}
