<?php

use BaoPham\EnvChecker\EnvCheckerCommand;


/**
 * Class EnvCheckerTest
 */
class EnvCheckerTest extends TestCase
{

    public function testParseEnvExample()
    {
        $checker = new EnvCheckerCommand();

        $variables = $checker->parseEnvExample(__DIR__ . '/.env.example');

        $this->assertCount(14, $variables);

        $this->assertEquals('bucket', $variables['S3_BUCKET']);

        $this->assertEquals('us-west-1', $variables['DYNAMODB_REGION']);

        $this->assertEquals(null, $variables['APP_URL']);
    }

    /**
     * @expectedException \BaoPham\EnvChecker\MissingVariableException
     * @expectedExceptionMessage Variables not configured in .env:
     * @expectedExceptionMessage Foo = default value for foo
     * @expectedExceptionMessage Bar
     * @expectedExceptionMessage Foobar
     */
    public function testEnvChecker()
    {
        $requiredVariables = [
            'Foo' => 'default value for foo',
            'Bar' => null,
            'Foobar' => ' ',
        ];

        $checker = new EnvCheckerCommand();

        $checker->checkForMissingVariables($requiredVariables);
    }

}

/**
 * Mock the env function.
 */
function env($name)
{
    return null;
}
