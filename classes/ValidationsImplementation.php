<?php

namespace Stanford\GoProd;

interface ValidationsImplementation
{

    public function getProject(): \Project;
    public function setProject(\Project $project): void;
    public function validate();
    public function getErrorMessage();
    public function getNotifications(): array;
    public function setNotifications(array $notifications): void;
}