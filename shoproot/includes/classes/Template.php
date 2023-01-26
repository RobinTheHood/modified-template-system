<?php

declare(strict_types=1);

namespace Modified\Core;

class Template
{
    public function getPath(string $templatePath): string
    {
        return DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/' . $templatePath;
    }

    public function getUrl(string $templatePath): string
    {
        return HTTP_SERVER . DIR_WS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/' . $templatePath;
    }

    public function fetch($smartyObj, string $templatePath)
    {
        return $smartyObj->fetch(CURRENT_TEMPLATE . '/' . $templatePath);
    }

    public function display($smartyObj, string $templatePath)
    {
        $smartyObj->display(CURRENT_TEMPLATE . '/' . $templatePath);
    }
}
