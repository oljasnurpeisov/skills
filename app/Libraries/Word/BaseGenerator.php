<?php

namespace Libraries\Word;

use App\Services\Files\StorageService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

abstract class BaseGenerator
{
    /**
     * Читаем шаблон
     *
     * @param string $filePath
     * @return TemplateProcessor
     */
    public function readTemplate(string $filePath): TemplateProcessor
    {
        $word = new \PhpOffice\PhpWord\PhpWord();
        $document = $word->loadTemplate($filePath);

        dd($document);

        try {
            if (file_exists($filePath)) {
                $this->templateProcessor = new TemplateProcessor($filePath);
            } else {
                $this->templateProcessor = new TemplateProcessor(public_path($filePath));
            }
        } catch (CopyFileException $e) {
            abort(500);
        } catch (CreateTemporaryFileException $e) {
            abort(500);
        }

        return $this->templateProcessor;
    }

    /**
     * Сохраняем шаблон
     *
     * @param string $savePath
     * @return void
     */
    public function TPSaveAs(string $savePath): void
    {
        $result = $this->templateProcessor->save();

        if(Storage::disk(env('SIGN_STORAGE', 'local'))->put($savePath, file_get_contents($result))) {
            @unlink($result);
        }
    }
}
