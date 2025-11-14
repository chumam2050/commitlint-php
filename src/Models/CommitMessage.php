<?php

namespace Choerulumam\CommitlintPhp\Models;

/**
 * Represents a parsed commit message following conventional commits format
 */
class CommitMessage
{
    private const CONVENTIONAL_COMMIT_PATTERN = '/^([a-z]+)(\(([^)]+)\))?(!)?:\s*(.+)$/';

    /** @var string */
    private $rawMessage;

    /** @var array<int, string> */
    private $lines;

    /** @var string|null */
    private $type;

    /** @var string|null */
    private $scope;

    /** @var string */
    private $subject;

    /** @var string */
    private $body;

    /** @var string */
    private $footer;

    /** @var bool */
    private $isBreakingChange;

    /**
     * @param string $rawMessage
     */
    public function __construct($rawMessage)
    {
        $this->rawMessage = $this->sanitizeMessage($rawMessage);
        $this->lines = explode("\n", $this->rawMessage);
        $this->body = '';
        $this->footer = '';
        $this->isBreakingChange = false;

        $parsed = $this->parseMessage();
        $this->type = $parsed['type'];
        $this->scope = $parsed['scope'];
        $this->subject = $parsed['subject'];
        $this->body = $parsed['body'];
        $this->footer = $parsed['footer'];
        $this->isBreakingChange = $parsed['isBreakingChange'];
    }

    /**
     * @param string $message
     * @return string
     */
    private function sanitizeMessage($message)
    {
        return trim($message);
    }

    /**
     * @return array<string, mixed>
     */
    private function parseMessage()
    {
        $firstLine = isset($this->lines[0]) ? $this->lines[0] : '';
        $isBreakingChange = false;
        
        if (preg_match(self::CONVENTIONAL_COMMIT_PATTERN, $firstLine, $matches)) {
            $type = $matches[1];
            $scope = isset($matches[3]) && $matches[3] !== '' ? $matches[3] : null;
            $hasExclamation = isset($matches[4]) && $matches[4] === '!';
            $subject = isset($matches[5]) ? $matches[5] : '';
            
            if ($hasExclamation) {
                $isBreakingChange = true;
            }
        } else {
            $type = null;
            $scope = null;
            $subject = $firstLine;
        }

        // Parse body and footer
        $bodyLines = [];
        $footerLines = [];
        $inFooter = false;
        $blankLineCount = 0;

        for ($i = 1; $i < count($this->lines); $i++) {
            $line = $this->lines[$i];
            
            if (trim($line) === '') {
                $blankLineCount++;
                continue;
            }

            if (preg_match('/^(BREAKING CHANGE:|[\w-]+:)/i', $line)) {
                $inFooter = true;
                if (stripos($line, 'BREAKING CHANGE:') === 0) {
                    $isBreakingChange = true;
                }
            }

            if ($inFooter) {
                $footerLines[] = $line;
            } else {
                $bodyLines[] = $line;
            }

            $blankLineCount = 0;
        }

        return [
            'type' => $type,
            'scope' => $scope,
            'subject' => $subject,
            'body' => implode("\n", $bodyLines),
            'footer' => implode("\n", $footerLines),
            'isBreakingChange' => $isBreakingChange,
        ];
    }

    /**
     * @param string $message
     * @return self
     */
    public static function fromString($message)
    {
        return new self($message);
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
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @return string
     */
    public function getRawMessage()
    {
        return $this->rawMessage;
    }

    /**
     * @return int
     */
    public function getSubjectLength()
    {
        return mb_strlen($this->subject);
    }

    /**
     * @return bool
     */
    public function hasValidFormat()
    {
        return $this->type !== null;
    }

    /**
     * @return bool
     */
    public function isBreakingChange()
    {
        return $this->isBreakingChange;
    }

    /**
     * @return bool
     */
    public function hasBlankLineAfterSubject()
    {
        if (empty($this->body) && empty($this->footer)) {
            return true;
        }

        return isset($this->lines[1]) && trim($this->lines[1]) === '';
    }

    /**
     * @return bool
     */
    public function hasBlankLineBeforeFooter()
    {
        if (empty($this->footer)) {
            return true;
        }

        // Find footer start
        for ($i = count($this->lines) - 1; $i >= 0; $i--) {
            if (preg_match('/^(BREAKING CHANGE:|[\w-]+:)/i', $this->lines[$i])) {
                return isset($this->lines[$i - 1]) && trim($this->lines[$i - 1]) === '';
            }
        }

        return false;
    }

    /**
     * Check if this commit should skip validation (merge, revert, fixup, etc.)
     *
     * @return bool
     */
    public function shouldSkipValidation()
    {
        $firstLine = isset($this->lines[0]) ? $this->lines[0] : '';
        
        $skipPatterns = [
            '/^Merge /',
            '/^Revert /',
            '/^Initial commit/',
            '/^fixup! /',
            '/^squash! /',
        ];

        foreach ($skipPatterns as $pattern) {
            if (preg_match($pattern, $firstLine)) {
                return true;
            }
        }

        return false;
    }
}
