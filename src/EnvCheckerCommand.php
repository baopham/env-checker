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
    protected $signature = 'envchecker:check';

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
        $envVariables = config('envchecker.variables', []);
        $missingVariables = [];

        foreach ($envVariables as $variable => $defaultValue) {
            $value = env($variable);
            if (!isset($value) || $value === '') {
                $missingVariables[] = $variable . ' = ' . $defaultValue;
            }
        }

        if (!empty($missingVariables)) {
            throw new MissingVariableException('Variables not configured in .env: ' . implode("\n", $missingVariables));
        }

        $this->info("Your .env has all the required variables configured.");
    }
}
