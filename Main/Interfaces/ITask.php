<?php

namespace EO\Interfaces;

interface ITask
{
    public function run();
    public function schedule();
}