<?php namespace Mednix\Login\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LoginInstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'login:install';

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
	public function __construct(Filesystem $files)
	{
        $this->files=$files;
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

        if($installed===true) {
            $this->error(':::::Package already installed:::::');
        }
        else{
            $this->info(':::::Installing the package...:::::');
                $this->info('   :::::Generating the migration file:::::');
                    $table=\Config::get('login::table');
                    $clazz=ucfirst($table);
                    $date=new \DateTime('now');
                    $format=$date->format('Y_m_d_His_');
                    $file=$format.'create_'.$table.'_table.php';
                    $migration=__DIR__.'/../../../migrations/'.$file;
                    $tpl=__DIR__.'/create_table.txt';
                    $content=$this->files->get($tpl);
                    $pattern='#table_name#';
                    $replacement=$table;
                    $content=str_replace($pattern,$replacement,$content);
                    $pattern='#class_name#';
                    $replacement=$clazz;
                    $content=str_replace($pattern,$replacement,$content);
                    $this->files->put($migration,$content);

                $this->info('   :::::Generating the user model:::::');
                    $pattern='#table_name#';
                    $replacement=$table;
                    $user=__DIR__.'/user.txt';
                    $content=$this->files->get($user);
                    $content=str_replace($pattern,$replacement,$content);
                    $model=app_path().'/models/User.php';
                    $this->files->put($model,$content);
                $this->info('   :::::Generating the the login layout:::::');
                    $layout=app_path().'/views/layouts/login.blade.php';
                    if(!$this->files->exists(dirname($layout))){
                        $this->files->makeDirectory(dirname($layout));
                    }

                    $content=$this->files->get(__DIR__.'/layouts.login.txt');
                    $this->files->put($layout,$content);
                $this->info('   ::::: dumping the composer autoloader:::::');
                    $chwd='cd '.__DIR__.'/../../../../.';
                    $composer=base_path().'/composer.phar';
                    $this->info(shell_exec($chwd.' && '.'php '.$composer.' dump-autoload'));

                $this->info('   :::::Executing the migration:::::');
                    $this->call('migrate',array('--bench'=>'mednix/login'));
                 \Config::set('login::installed',true);
            $this->info(':::::Package installed successfully!:::::');

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