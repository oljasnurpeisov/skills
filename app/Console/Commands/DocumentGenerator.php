<?php

namespace App\Console\Commands;

use App\Models\AVR;
use App\Models\Contract;
use Illuminate\Console\Command;
use Services\Contracts\AVRService;
use Services\Contracts\ContractService;

/**
 * Class DocumentGenerator
 * @package App\Console\Commands
 *
 * Generate or regenerate contracts and acts
 */
class DocumentGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:document {id} {type=contract}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate PDF document (or regenerate if exists)';

    /**
     * @var ContractService
     */
    private $contractService;

    /** @var AVRService */
    private $actService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ContractService $contractService, AVRService $actService)
    {
        parent::__construct();

        $this->contractService = $contractService;
        $this->actService = $actService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @throws \Mpdf\MpdfException
     */
    public function handle()
    {
        $id = $this->argument('id');
        $type = $this->argument('type');

        $result = false;

        switch ($type) {
            case 'contract':
                $result = $this->generateContract($id);
                break;
            case 'act':
                $result = $this->generateAct($id);
                break;
        }

        return $result ? 0 : 1;
    }

    /**
     * Generate contract
     * @param int $id
     * @return bool
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    private function generateContract(int $id): bool
    {
        /** @var Contract $model */
        $model = Contract::where('id', $id)->first();

        if (!$model) {
            $this->error(sprintf('Договор %s не найден', $id));
            return false;
        }

        $this->info(sprintf('Обработка договора %d начата', $id));

        if (!$model->document) {
            $this->error('Договор не содержит электронный документ');
            return false;
        }

        $link = $this->contractService->contractToPdf($id, true);

        if ($link) {
            $this->info('Документ успешно сформирован:');
            $this->info($link);
            return true;
        }

        $this->error('При генерации документа возникла ошибка');
        return false;
    }

    /**
     * Generate act
     * @param int $id
     * @return bool
     * @throws \Mpdf\MpdfException
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    private function generateAct(int $id): bool
    {
        $model = AVR::where('id', $id)->first();

        if (!$model) {
            $this->error(sprintf('Акт %s не найден', $id));
            return false;
        }

        $this->info(sprintf('Обработка акта %d начата', $id));

        if (!$model->document) {
            $this->error('Акт не содержит электронный документ');
            return false;
        }

        $link = $this->actService->avrToPdf($id, true);

        if ($link) {
            $this->info('Документ успешно сформирован:');
            $this->info($link);
            return true;
        }

        $this->error('При генерации документа возникла ошибка');
        return false;
    }
}
