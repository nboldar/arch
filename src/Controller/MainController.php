<?php

declare(strict_types = 1);

namespace Controller;

use Framework\Render;
use Symfony\Component\HttpFoundation\Response;
use Traits\MonologLogger;


class MainController
{
    use Render;
    use MonologLogger;

    /**
     * Главная страница
     *
     * @return Response
     */
    public function indexAction(): Response
    {


      $this->setMonologLogger();
      $this->monologLogger->info(__METHOD__ . ' take memory: ' . memory_get_peak_usage(true));
      return  $this->render('main/index.html.php');
    }
}
