<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Settings\General;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        view()->share('language', Language::where('locale', app()->getLocale())->exists() ? app()->getLocale() : app(General::class)->default_locale);
        view()->share('rtl', app()->getLocale() == "ar" ? "true" : "false");
    }

    private function removeInlineStyle($node, &$output_style): void
    {
        foreach ($node->childNodes as $child){
            if ($node instanceof \DOMText)
                continue;

            if ($inline_style = $node->getAttribute('style')){
                $node->removeAttribute('style');
                $inline_class_name = "inline-" . uuid_create();
                $output_style .= ".{$inline_class_name}{ {$inline_style} }\n";

                if ($element_class_name = $node->getAttribute('class'))
                    $node->removeAttribute('class');

                $element_class_name .= " " . $inline_class_name;
                $node->setAttribute('class', trim($element_class_name));
            }

            if ($node->hasChildNodes())
                $this->removeInlineStyle($child, $output_style);
        }
    }

    public function view($viewPath, $content = [], $status=200, $headers=[]): bool|\Illuminate\Http\JsonResponse|string
    {
        if (request()->ajax())
            return response()->json($content, $status, $headers);
        $rendered_html = view($viewPath, $content)->render();
        if (config('app.env') != "local")
            $rendered_html = str_replace('site.', '', $rendered_html);
        $html = new \DOMDocument;
        libxml_use_internal_errors(true);
        $html->loadHTML($rendered_html);
        libxml_clear_errors();
        $style = "";
        $this->removeInlineStyle($html->getElementsByTagName('body')->item(0), $style);
        $inline_style_element = $html->createElement('style', $style);
        $html->getElementsByTagName('head')->item(0)->appendChild($inline_style_element);
        return $html->saveHTML();
    }
}
