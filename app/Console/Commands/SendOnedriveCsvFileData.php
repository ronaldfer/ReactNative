<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendOnedriveCsvFileData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendOnedriveCsvFileData:uploadfile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is send onedrive pallets file data in to database and run shell script';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info(date('Y-m-d H:i:s').'RUN THE COMMNAD');
//
        $import_pallets_file_data = shell_exec('sh SHELL-CRONS/import_pallets_file_data.sh');
        $import_customer_portal_summary_file_data = shell_exec('sh SHELL-CRONS/import_customer_portal_summary_file_data.sh');
        $import_customer_portal_mail_file_datas = shell_exec('sh SHELL-CRONS/import_customer_portal_mail_file_data.sh');


        // return 0;
    }
}
