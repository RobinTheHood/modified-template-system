<?php

declare(strict_types=1);

namespace Modified\Core;

class Template
{
    public function fetch($smartyObj, string $templatePath)
    {
        return $smartyObj->fetch(CURRENT_TEMPLATE . '/' . $templatePath);
    }

    public function display($smartyObj, string $templatePath)
    {
        $smartyObj->display(CURRENT_TEMPLATE . '/' . $templatePath);
    }
}
