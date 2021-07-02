<?php

namespace Libraries\Word;

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
        try {
            $this->templateProcessor = new TemplateProcessor(public_path($filePath));
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
        $this->templateProcessor->saveAs(public_path($savePath));
    }
}
