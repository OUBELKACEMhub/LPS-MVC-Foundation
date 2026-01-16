<?php
namespace Core;

class Controller {
    public function render($template, $data = []) {
        extract($data);
        include __DIR__ . "/Template/" . $template . "html.twig";
    }
}
