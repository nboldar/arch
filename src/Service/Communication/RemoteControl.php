<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 27.01.2019
 * Time: 19:19
 */

namespace Service\Communication;


class RemoteControl
{
    public function submit (ICommand $command, $user, $template, $params = [])
    {
        echo 'Некоторая бизнес-логика';

        $command->process($user, $template, $params);

        echo 'Некоторая бизнес-логика';
    }

}