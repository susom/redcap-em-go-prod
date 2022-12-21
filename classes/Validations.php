<?php

namespace Stanford\GoProd;

class Validations
{
    /**
     * @var \Project
     */
    private $project;

    /**
     * @var array
     */
    private $enabledRules;
    public function __construct($project)
    {
        $this->setProject($project);
    }

    /**
     * @return \Project
     */
    public function getProject(): \Project
    {
        return $this->project;
    }

    /**
     * @param \Project $project
     */
    public function setProject(\Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return array
     */
    public function getEnabledRules(): array
    {
        return $this->enabledRules;
    }

    /**
     * @param array $enabledRules
     */
    public function setEnabledRules(array $enabledRules): void
    {
        $this->enabledRules = $enabledRules;
    }
}