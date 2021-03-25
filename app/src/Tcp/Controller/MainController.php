<?php

namespace App\Tcp\Controller;

use App\Tcp\Constant\Defaults;

class MainController extends BaseController
{
    public function main(array $data)
    {

        $this->response(["request" => $data, "response" => Defaults::OK]);
    }
}