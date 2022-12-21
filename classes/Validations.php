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

    public static function CleanString($string)
    {

        // first preparing the strings.
        //for $string1
        //remove spaces at the end and convert in lowercase
        $word1 = trim(strtolower($string));
        //remove spaces between words
        $word1 = str_replace(' ', '_', $word1);
        //remove tabs
        $word1 = preg_replace('/\s+/', '_', $word1);
        $word1 = str_replace('__', '_', $word1);

        return $word1;
    }
}