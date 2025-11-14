<?php

namespace Choerulumam\CommitlintPhp\Services;

use Choerulumam\CommitlintPhp\Models\CommitMessage;
use Choerulumam\CommitlintPhp\Models\ValidationResult;

/**
 * Service for validating commit messages against configured rules
 */
class ValidationService
{
    private const MAX_MESSAGE_LENGTH = 10000;

    /**
     * Validate a commit message against configuration rules
     *
     * @param string $message
     * @param array<string, mixed> $config
     * @return ValidationResult
     */
    public function validate($message, array $config)
    {
        if (empty(trim($message))) {
            return ValidationResult::error('Commit message cannot be empty');
        }

        if (strlen($message) > self::MAX_MESSAGE_LENGTH) {
            return ValidationResult::error('Commit message too long (max ' . number_format(self::MAX_MESSAGE_LENGTH) . ' characters)');
        }

        try {
            $commitMessage = CommitMessage::fromString($message);

            // Skip validation for special commits
            if ($commitMessage->shouldSkipValidation()) {
                return ValidationResult::valid($commitMessage->getType(), $commitMessage->getScope());
            }

            return $this->validateCommitMessage($commitMessage, $config);
        } catch (\Exception $e) {
            return ValidationResult::error('Validation error: ' . $e->getMessage());
        }
    }

    /**
     * @param CommitMessage $commitMessage
     * @param array<string, mixed> $config
     * @return ValidationResult
     */
    private function validateCommitMessage(CommitMessage $commitMessage, array $config)
    {
        $errors = [];

        $errors = array_merge($errors, $this->validateFormat($commitMessage, $config));
        $errors = array_merge($errors, $this->validateType($commitMessage, $config));
        $errors = array_merge($errors, $this->validateScope($commitMessage, $config));
        $errors = array_merge($errors, $this->validateSubject($commitMessage, $config));
        $errors = array_merge($errors, $this->validateBody($commitMessage, $config));
        $errors = array_merge($errors, $this->validateFooter($commitMessage, $config));

        return empty($errors)
            ? ValidationResult::valid($commitMessage->getType(), $commitMessage->getScope())
            : ValidationResult::invalid($errors, $commitMessage->getType(), $commitMessage->getScope());
    }

    /**
     * @param CommitMessage $commitMessage
     * @param array<string, mixed> $config
     * @return array<int, string>
     */
    private function validateFormat(CommitMessage $commitMessage, array $config)
    {
        if (!$commitMessage->hasValidFormat()) {
            return ['Commit message must follow conventional commit format: type(scope): description'];
        }

        return [];
    }

    /**
     * @param CommitMessage $commitMessage
     * @param array<string, mixed> $config
     * @return array<int, string>
     */
    private function validateType(CommitMessage $commitMessage, array $config)
    {
        $typeConfig = isset($config['rules']['type']) ? $config['rules']['type'] : [];
        $type = $commitMessage->getType();

        if (empty($type)) {
            return ['Commit type is required'];
        }

        $allowedTypes = isset($typeConfig['allowed']) ? $typeConfig['allowed'] : [];
        if (!empty($allowedTypes) && !in_array($type, $allowedTypes, true)) {
            return [sprintf(
                'Invalid commit type "%s". Allowed types: %s',
                $type,
                implode(', ', $allowedTypes)
            )];
        }

        return [];
    }

    /**
     * @param CommitMessage $commitMessage
     * @param array<string, mixed> $config
     * @return array<int, string>
     */
    private function validateScope(CommitMessage $commitMessage, array $config)
    {
        $scopeConfig = isset($config['rules']['scope']) ? $config['rules']['scope'] : [];
        $scope = $commitMessage->getScope();

        $isRequired = isset($scopeConfig['required']) ? $scopeConfig['required'] : false;
        if ($isRequired && empty($scope)) {
            return ['Commit scope is required'];
        }

        if (!empty($scope)) {
            $allowedScopes = isset($scopeConfig['allowed']) ? $scopeConfig['allowed'] : [];
            if (!empty($allowedScopes) && !in_array($scope, $allowedScopes, true)) {
                return [sprintf(
                    'Invalid commit scope "%s". Allowed scopes: %s',
                    $scope,
                    implode(', ', $allowedScopes)
                )];
            }
        }

        return [];
    }

    /**
     * @param CommitMessage $commitMessage
     * @param array<string, mixed> $config
     * @return array<int, string>
     */
    private function validateSubject(CommitMessage $commitMessage, array $config)
    {
        $subjectConfig = isset($config['rules']['subject']) ? $config['rules']['subject'] : [];
        $subject = $commitMessage->getSubject();
        $errors = [];

        if (empty($subject)) {
            return ['Commit subject is required'];
        }

        $minLength = isset($subjectConfig['min_length']) ? $subjectConfig['min_length'] : 1;
        $maxLength = isset($subjectConfig['max_length']) ? $subjectConfig['max_length'] : 100;
        $subjectLength = $commitMessage->getSubjectLength();

        if ($subjectLength < $minLength) {
            $errors[] = sprintf('Subject too short (minimum %d characters)', $minLength);
        }

        if ($subjectLength > $maxLength) {
            $errors[] = sprintf('Subject too long (maximum %d characters)', $maxLength);
        }

        $case = isset($subjectConfig['case']) ? $subjectConfig['case'] : 'any';
        if ($case !== 'any') {
            $firstChar = mb_substr($subject, 0, 1);
            if ($case === 'lower' && $firstChar !== mb_strtolower($firstChar)) {
                $errors[] = 'Subject must start with lowercase letter';
            } elseif ($case === 'upper' && $firstChar !== mb_strtoupper($firstChar)) {
                $errors[] = 'Subject must start with uppercase letter';
            }
        }

        $endWithPeriod = isset($subjectConfig['end_with_period']) ? $subjectConfig['end_with_period'] : false;
        if ($endWithPeriod && !preg_match('/\.$/', $subject)) {
            $errors[] = 'Subject must end with a period';
        } elseif (!$endWithPeriod && preg_match('/\.$/', $subject)) {
            $errors[] = 'Subject must not end with a period';
        }

        return $errors;
    }

    /**
     * @param CommitMessage $commitMessage
     * @param array<string, mixed> $config
     * @return array<int, string>
     */
    private function validateBody(CommitMessage $commitMessage, array $config)
    {
        $bodyConfig = isset($config['rules']['body']) ? $config['rules']['body'] : [];
        $body = $commitMessage->getBody();
        $errors = [];

        if (empty($body)) {
            return [];
        }

        $leadingBlank = isset($bodyConfig['leading_blank']) ? $bodyConfig['leading_blank'] : true;
        if ($leadingBlank && !$commitMessage->hasBlankLineAfterSubject()) {
            $errors[] = 'Body must be separated from subject by a blank line';
        }

        $maxLineLength = isset($bodyConfig['max_line_length']) ? $bodyConfig['max_line_length'] : 100;
        if ($maxLineLength > 0) {
            $bodyLines = explode("\n", $body);
            foreach ($bodyLines as $lineNumber => $line) {
                if (mb_strlen($line) > $maxLineLength) {
                    $errors[] = sprintf('Body line %d exceeds maximum length of %d characters', $lineNumber + 1, $maxLineLength);
                }
            }
        }

        return $errors;
    }

    /**
     * @param CommitMessage $commitMessage
     * @param array<string, mixed> $config
     * @return array<int, string>
     */
    private function validateFooter(CommitMessage $commitMessage, array $config)
    {
        $footerConfig = isset($config['rules']['footer']) ? $config['rules']['footer'] : [];
        $footer = $commitMessage->getFooter();

        if (empty($footer)) {
            return [];
        }

        $leadingBlank = isset($footerConfig['leading_blank']) ? $footerConfig['leading_blank'] : true;
        if ($leadingBlank && !$commitMessage->hasBlankLineBeforeFooter()) {
            return ['Footer must be separated from body by a blank line'];
        }

        return [];
    }
}
