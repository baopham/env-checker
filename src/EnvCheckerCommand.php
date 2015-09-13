<?php

namespace BaoPham\EnvChecker;

use Illuminate\Console\Command;


/**
 * Class EnvCheckerCommand
 */
class EnvCheckerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:check {--env-example= : path to the .env.example}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the .env if it has all the required variables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $envExamplePath = $this->option('env-example');

        $requiredVariables = $this->parseEnvExample($envExamplePath);

        $this->checkForMissingVariables($requiredVariables);

        $this->info("Your .env has all the required variables configured.");
    }

    public function checkForMissingVariables($requiredVariables)
    {
        $missingVariables = [];

        foreach ($requiredVariables as $variable => $defaultValue) {
            $value = env($variable);
            if (! isset($value) || $value === '') {

                if (! empty($defaultValue)) {
                    $missingVariables[] = $variable;
                    continue;
                }

                $missingVariables[] = $variable . ' = ' . $defaultValue;
            }
        }

        if (! empty($missingVariables)) {
            throw new MissingVariableException('Variables not configured in .env: ' . "\n" . implode("\n",
                    $missingVariables));
        }
    }

    public function parseEnvExample($envExamplePath = null)
    {
        if (is_null($envExamplePath)) {
            $envExamplePath = base_path() . '/.env.example';
        }

        $this->checkValidFile($envExamplePath);

        $variables = [];

        $lines = file($envExamplePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Disregard comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Only use non-empty lines that look like setters
            if (strpos($line, '=') !== false) {
                list($name, $value) = static::splitCompoundStringIntoParts($line);
                $variables[$name] = $value;
            }
        }

        return $variables;
    }

    /**
     * If the $name contains an = sign, then we split it into 2 parts, a name & value.
     * @see https://github.com/vlucas/phpdotenv/blob/1.1/src/Dotenv.php#L164
     *
     * @param string $name
     * @param string $value
     *
     * @return array
     */
    protected static function splitCompoundStringIntoParts($name, $value = null)
    {
        if (strpos($name, '=') !== false) {
            list($name, $value) = array_map('trim', explode('=', $name, 2));
        }

        return array($name, $value);
    }

    protected function checkValidFile($filePath)
    {
        if (! is_readable($filePath) || ! is_file($filePath)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'EnvChecker: file %s not found or not readable. ',
                    $filePath
                )
            );
        }
    }
}
