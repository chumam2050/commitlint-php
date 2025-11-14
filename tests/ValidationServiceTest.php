<?php

namespace Choerulumam\CommitlintPhp\Tests;

use Choerulumam\CommitlintPhp\Services\ValidationService;
use PHPUnit\Framework\TestCase;

class ValidationServiceTest extends TestCase
{
    /** @var ValidationService */
    private $validator;

    /** @var array<string, mixed> */
    private $defaultConfig;

    protected function setUp(): void
    {
        $this->validator = new ValidationService();
        $this->defaultConfig = [
            'rules' => [
                'type' => [
                    'required' => true,
                    'allowed' => ['feat', 'fix', 'docs', 'style', 'refactor', 'test', 'chore'],
                ],
                'scope' => [
                    'required' => false,
                    'allowed' => [],
                ],
                'subject' => [
                    'min_length' => 1,
                    'max_length' => 100,
                    'case' => 'any',
                    'end_with_period' => false,
                ],
                'body' => [
                    'max_line_length' => 100,
                    'leading_blank' => true,
                ],
                'footer' => [
                    'leading_blank' => true,
                ],
            ],
        ];
    }

    public function testValidatesValidConventionalCommitMessage()
    {
        $message = 'feat: add user authentication';
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
        $this->assertEquals('feat', $result->getType());
    }

    public function testValidatesValidCommitMessageWithScope()
    {
        $message = 'feat(auth): add user authentication';
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertTrue($result->isValid());
        $this->assertEquals('feat', $result->getType());
        $this->assertEquals('auth', $result->getScope());
    }

    public function testRejectsInvalidCommitType()
    {
        $message = 'invalid: add user authentication';
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertFalse($result->isValid());
        $this->assertNotEmpty($result->getErrors());
        $this->assertStringContainsString('Invalid commit type', $result->getErrors()[0]);
    }

    public function testRejectsCommitMessageWithoutType()
    {
        $message = 'add user authentication';
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertFalse($result->isValid());
        $this->assertNotEmpty($result->getErrors());
    }

    public function testRejectsEmptyCommitMessage()
    {
        $message = '';
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertFalse($result->isValid());
        $this->assertStringContainsString('cannot be empty', $result->getErrors()[0]);
    }

    public function testValidatesSubjectLength()
    {
        $config = $this->defaultConfig;
        $config['rules']['subject']['max_length'] = 20;

        $message = 'feat: this is a very long subject that exceeds the maximum length';
        $result = $this->validator->validate($message, $config);

        $this->assertFalse($result->isValid());
        $this->assertStringContainsString('Subject too long', $result->getErrors()[0]);
    }

    public function testSkipsValidationForMergeCommits()
    {
        $message = 'Merge branch "feature" into main';
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertTrue($result->isValid());
    }

    public function testSkipsValidationForRevertCommits()
    {
        $message = 'Revert "feat: add user authentication"';
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertTrue($result->isValid());
    }

    public function testValidatesRequiredScope()
    {
        $config = $this->defaultConfig;
        $config['rules']['scope']['required'] = true;

        $message = 'feat: add authentication';
        $result = $this->validator->validate($message, $config);

        $this->assertFalse($result->isValid());
        $this->assertStringContainsString('Commit scope is required', $result->getErrors()[0]);
    }

    public function testValidatesAllowedScopes()
    {
        $config = $this->defaultConfig;
        $config['rules']['scope']['allowed'] = ['auth', 'ui', 'api'];

        $message = 'feat(invalid): add authentication';
        $result = $this->validator->validate($message, $config);

        $this->assertFalse($result->isValid());
        $this->assertStringContainsString('Invalid commit scope', $result->getErrors()[0]);
    }

    public function testValidatesSubjectCase()
    {
        $config = $this->defaultConfig;
        $config['rules']['subject']['case'] = 'lower';

        $message = 'feat: Add authentication';
        $result = $this->validator->validate($message, $config);

        $this->assertFalse($result->isValid());
        $this->assertStringContainsString('lowercase', $result->getErrors()[0]);
    }

    public function testValidatesBodyBlankLine()
    {
        $message = "feat: add authentication\nThis is a body without blank line";
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertFalse($result->isValid());
        $this->assertStringContainsString('blank line', $result->getErrors()[0]);
    }

    public function testAcceptsValidCommitWithBody()
    {
        $message = "feat: add authentication\n\nThis is a properly formatted body";
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertTrue($result->isValid());
    }

    public function testValidatesBreakingChangeFormat()
    {
        $message = "feat!: redesign authentication\n\nBREAKING CHANGE: The auth API has changed.";
        $result = $this->validator->validate($message, $this->defaultConfig);

        $this->assertTrue($result->isValid());
    }
}
