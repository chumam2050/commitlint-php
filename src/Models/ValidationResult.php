<?php

namespace Choerulumam\CommitlintPhp\Models;

/**
 * Represents the result of a commit message validation
 */
class ValidationResult
{
    /** @var bool */
    private $isValid;

    /** @var array<int, string> */
    private $errors;

    /** @var string|null */
    private $type;

    /** @var string|null */
    private $scope;

    /**
     * @param bool $isValid
     * @param array<int, string> $errors
     * @param string|null $type
     * @param string|null $scope
     */
    public function __construct($isValid, array $errors = [], $type = null, $scope = null)
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
        $this->type = $type;
        $this->scope = $scope;
    }

    /**
     * @param string|null $type
     * @param string|null $scope
     * @return self
     */
    public static function valid($type = null, $scope = null)
    {
        return new self(true, [], $type, $scope);
    }

    /**
     * @param array<int, string> $errors
     * @param string|null $type
     * @param string|null $scope
     * @return self
     */
    public static function invalid(array $errors, $type = null, $scope = null)
    {
        return new self(false, $errors, $type, $scope);
    }

    /**
     * @param string $errorMessage
     * @return self
     */
    public static function error($errorMessage)
    {
        return new self(false, [$errorMessage]);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * @return array<int, string>
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * @return int
     */
    public function getErrorCount()
    {
        return count($this->errors);
    }
}
