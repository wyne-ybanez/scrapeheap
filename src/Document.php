<?php

namespace Coderjerk\Scrapeheap;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

class Document
{
    /**
     * Creates anf Formats an MS Word document.
     *
     * @param string $target_url
     * @param string $title
     * @param string $content
     * @return void
     */
    public static function make(string $target_url, string $title, string $content): void
    {
        $phpWordTemplate = new PhpWord;

        Settings::setOutputEscapingEnabled(true);

        $phpWordTemplate->addTitleStyle(
            1,
            ['bold' => true, 'size' => 32],
            ['spaceAfter' => 640]
        );

        // New portrait section
        $section = $phpWordTemplate->addSection();

        // Simple text
        $section->addTitle($title, 1);
        $section->addLink($target_url, $target_url);

        // Two text break
        $section->addTextBreak(2);

        $section->addText($content);

        $section->addTextBreak(4);

        // Link
        $section->addLink($target_url, 'View Page');
        $section->addTextBreak(2);

        $domain = parse_url($target_url, PHP_URL_HOST);

       // Save files
       $pathDocx = "output/Docx/{$domain}/";
       self::saveDocx($phpWordTemplate, $pathDocx, $title);

       $pathHTML = "output/HTML/{$domain}/";
       self::saveHTML($phpWordTemplate, $pathHTML, $title);
    }

    /**
     * Saves into HTML Document
     *
     * @param  $phpWordTemplate
     * @param  $path
     * @param  $title
     * @return void
     */
    public static function saveHTML($phpWordTemplate, $path, $title) {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $objWriter = IOFactory::createWriter($phpWordTemplate, 'HTML');
        $objWriter->save("{$path}{$title}.html");
    }

    /**
     * Saves as Word Doc
     *
     * @param  $phpWordTemplate
     * @param  $path
     * @param  $title
     * @return void
     */
    public static function saveDocx($phpWordTemplate, $path, $title) {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $objWriter = IOFactory::createWriter($phpWordTemplate, 'Word2007');
        $objWriter->save("{$path}{$title}.docx");
    }
}
