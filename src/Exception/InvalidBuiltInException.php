<?php

declare(strict_types=1);

namespace Jaddek\Arranger\Exception;

/**
 * Class InvalidBuiltInException
 */
class InvalidBuiltInException extends \Exception
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var
     */
    private $value;
    /**
     * @var \ReflectionParameter
     */
    private $parameter;
    /**
     * @var \ReflectionClass
     */
    private $class;


    /**
     * InvalidBuiltInException constructor.
     *
     * @param string               $type
     * @param                      $value
     * @param \ReflectionParameter $parameter
     * @param \ReflectionClass     $class
     */
    public function __construct(string $type, $value, \ReflectionParameter $parameter, \ReflectionClass $class)
    {
        $this->type      = $type;
        $this->value     = $value;
        $this->parameter = $parameter;
        $this->class     = $class;

        parent::__construct("Invalid built in argument", 0, null);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return \ReflectionParameter
     */
    public function getParameter(): \ReflectionParameter
    {
        return $this->parameter;
    }

    /**
     * @return \ReflectionClass
     */
    public function getClass(): \ReflectionClass
    {
        return $this->class;
    }
}