<?php

    /**
     * Name: Payslip FTP sender
     * Author: BytesCrafter
     * Version: v0.1.0
     */

    require '../config.php';
    require '../vendor/autoload.php';
    include './excel.php';
    use Mailgun\Mailgun;

    // First, instantiate the SDK with your API credentials
    $mg = Mailgun::create( $config['apikey'] ); // For US servers
    $mg = Mailgun::create( $config['apikey'] , $config['smtp_host']); // For EU servers

    $pending = './uploads/pending';
    $files = scandir($pending);

    foreach($files as $file) {

        $file_path = $pending."/".$file;

        if( !is_dir($file_path) && (strpos($file, '.xls') !== false || strpos($file, '.xlsx') !== false)) {

            //echo $file_path."<br>";
            $result = array( "success" => false );
            $file_name = rtrim($file,".xls");

            try {
                //ExcelToPDF::convert($file_path);

                // Now, compose and send your message.
                $response = $mg->messages()->send('ahmoutsourcing.com', [
                    'h:Reply-To' => 'hr-payroll@ahmoutsourcing.com',
                    'from'    => 'noreply@ahmoutsourcing.com',
                    'to'      => $file_name.'@ahmoutsourcing.com',
                    'subject' => 'AHM Outsourcing Inc - Paylslip',
                    'text'    => 'Hello, heres your payslip.',
                    'attachment' => [
                        ['filePath'=>$file_path, 'filename'=>'payslip.xls']
                    ]
                ]);

                rename($file_path, dirname(__FILE__)."/uploads/finished/".$file);
                $result['success'] = true;
                $result['message'] = "Successfully queued the email.";
                $result['response'] = $response;
            } catch (Exception $e) {

                rename($file_path, dirname(__FILE__)."/uploads/failed/".$file);
                $result['message'] = $e->getMessage();
            }
            
            echo json_encode($result);
            
        }

    }
    