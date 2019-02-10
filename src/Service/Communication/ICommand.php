<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 27.01.2019
 * Time: 19:09
 */

namespace Service\Communication;


interface ICommand
{
    public function process (\Model\Entity\User $user, string $templateName, array $params = []);
}