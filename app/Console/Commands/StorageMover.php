<?php

namespace App\Console\Commands;

use App\Models\AVR;
use App\Models\Contract;
use App\Services\Files\StorageService;
use Illuminate\Console\Command;

class StorageMover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:organize {id=all} {type=contract}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Organize storage structure';

    /**
     * @var StorageService
     */
    private $storageService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(StorageService $storageService)
    {
        parent::__construct();

        $this->storageService = $storageService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->argument('type');
        $id = $this->argument('id');

        switch ($type) {

            case 'contract':

                $this->info('Обработка договоров');

                if (is_numeric($id)) {
                    $contracts = Contract::where('id', $id)->get();
                } else {
                    $contracts = Contract::all();
                }

                $savePath = 'documents/'. date('Y') . '/contracts/%s/';

                foreach ($contracts as $contract) {

                    if (!$contract->number) continue;

                    $this->info('Обработка договора ' . $contract->number);

                    $link = $contract->link;
                    $data = public_path($link);

                    if (file_exists($data)) {

                        $info = pathinfo($data);
                        $path = sprintf($savePath, $contract->number) . $info['basename'];

                        $this->info('Обработка файла: ' . $data);
                        $this->info('Новое расположение: ' . $path);

                        if($this->storageService->save($path, file_get_contents($data))) {
                            $this->info('Файл перенесен');
                            @unlink($data);
                        } else {
                            $this->warn('Ошибка переноса файла');
                        }

                        $originalLink = null;

                        if ($info['extension'] === 'pdf') {

                            $originalLink = preg_replace('/pdf/', 'docx', $link);
                            $originalData = public_path($originalLink);

                            if (file_exists($originalData)) {

                                $info = pathinfo($originalData);
                                $path = sprintf($savePath, $contract->number) . $info['basename'];

                                $this->info('Обработка файла: ' . $originalData);
                                $this->info('Новое расположение: ' . $path);

                                if($this->storageService->save($path, file_get_contents($originalData))) {
                                    $this->info('Файл перенесен');
                                    @unlink($originalData);
                                } else {
                                    $this->warn('Ошибка переноса файла');
                                }
                            }
                        }

                        if ($contract->document && $contract->document->content) {

                            $path = sprintf($savePath, $contract->number) . str_replace(['.pdf', '.docx'], '.xml', $info['basename']);

                            if($this->storageService->save($path, $contract->document->content)) {
                                $this->info('Файл перенесен');
                                $contract->document->content = null;
                                $contract->document->save();
                            } else {
                                $this->warn('Ошибка переноса файла');
                            }
                        }
                    }
                }

                break;

            case 'act':

                $this->info('Обработка актов');
                $this->warn('Не реализовано');

                $savePath = 'documents/'. date('Y') . '/acts/%d/';

                break;
        }


        return 0;
    }
}
