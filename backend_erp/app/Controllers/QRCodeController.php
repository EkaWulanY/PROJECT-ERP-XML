<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeController extends Controller
{
    public function job($id_job)
    {
        // URL form lamaran (frontend / form view)
        $url = base_url("lamaran/" . $id_job);

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 250,
            margin: 10,
            labelText: "Job #$id_job",
            labelAlignment: LabelAlignment::Center
        );

        $result = $builder->build();

        return $this->response
            ->setContentType($result->getMimeType())
            ->setBody($result->getString());
    }

    public function sistem()
    {
        $url = "http://localhost:8000/lowongan-kerja";

        $builder = new Builder(
            writer: new PngWriter(),
            data: $url,
            size: 300,
            margin: 10,
            labelText: "XML-KARIR"
        );

        $result = $builder->build();

        $response = $this->response->setContentType($result->getMimeType());

        if ($this->request->getGet('download')) {
            // kasih header untuk download
            $response->setHeader('Content-Disposition', 'attachment; filename="qrcode-lowongan.png"');
        }

        return $response->setBody($result->getString());
    }
}
