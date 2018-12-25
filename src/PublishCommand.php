<?php

namespace Field\Interaction;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:publish {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "re-publish FieldInteraction's assets, configuration, language and migration files. If you want overwrite the existing files, you can add the `--force` option";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $options = ['--provider' => 'Field\Interaction\InteractionServiceProvider'];
        $this->call('vendor:publish', $options);
    }
}