<?php namespace Mednix\Login\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LoginUninstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'login:uninstall';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Filesystem $files,MigrationRepositoryInterface $repository,Migrator $migrator)
	{
        $this->files=$files;
        $this->repository=$repository;
        $this->migrator=$migrator;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
        $installed=\Config::get('login::installed',false);
        $installed=true;
        if(!$installed) {
            $this->error(':::::Package not installed:::::');
        }
        else{
            $this->info(':::::Uninstalling the package...:::::');

                $this->info('   :::::Rolling back the migration:::::');
                    $dir=__DIR__."/../../../migrations";
                    $migration=new \stdClass();
                    $file=basename($this->files->files($dir)[0],'.php');
                    $migration->migration=$file;
                    $instance = $this->migrator->resolve($file);
                    $instance->down();
                    $this->repository->delete($migration);
                    $this->files->cleanDirectory($dir);

                $this->info('   :::::Deleting the user model:::::');
                    $model=app_path().'/models/User.php';
                    $this->files->delete($model);
                $this->info('   :::::Deleting the login layout:::::');
                    $layout=app_path().'/views/layouts/login.blade.php';
                    $this->files->delete($layout);
                $this->info('   ::::: dumping the composer autoloader:::::');
                    $chwd='cd '.__DIR__.'/../../../../.';
                    $composer=base_path().'/composer.phar';
                    $this->info(shell_exec($chwd.' && '.'php '.$composer.' dump-autoload'));

                \Config::set('login::installed',false);

            $this->info(':::::The package has been uninstalled!...:::::');
        }

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}