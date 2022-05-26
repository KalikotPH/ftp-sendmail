<?php

    /**
     * Name: Payslip FTP sender
     * Author: BytesCrafter
     * Version: v0.1.0
     */

    require '../config.php';
    require '../vendor/autoload.php';

    use \PhpOffice\PhpSpreadsheet\IOFactory;
    use \PhpOffice\PhpSpreadsheet\Style\Border;

    class ExcelToPDF {
        function __construct() {

        }

        public static function convert($file_path) {
            $reader = IOFactory::createReaderForFile($file_path);
            $phpWord = $reader->load($file_path);
        
                $phpWord ->getDefaultStyle()->applyFromArray(
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ]
                    ]
                );
        
            $xmlWriter = IOFactory::createWriter($phpWord,'Mpdf');
            $xmlWriter->writeAllSheets();
            //$xmlWriter->setFooter("Made Possible by BytesCrafter");
            $xmlWriter->save("./uploads/pending/test.pdf");
        }
    }
    