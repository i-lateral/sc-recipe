<?php
namespace Deployer;

use Deployer\Task\Context;

require 'recipe/silverstripe.php';

// Project Info
set('org', '__OrgName__');
set('application', '__ApplicationName__');

// Historic releases
set('keep_releases', 3);

// Project repository
set('repository', 'git@bitbucket.org:{{org}}/{{application}}.git');

// Deployment Info
set('default_stage', 'dev');
set('branch_dev', 'dev');
set('branch_live', 'master');
set('deploy_live_user', '__LiveUserName_');
set('deploy_dev_user', '__DevUserName_');
set('ssh_port_dev', 22);
set('ssh_port_live', 22);

// Silverstripe shared dirs
set(
    'shared_dirs',
    [
        'public/assets',
        'silverstripe-cache'
    ]
);

// Which folders are writable
set(
    'writable_dirs',
    [
        'themes',
        'public/assets',
        'silverstripe-cache'
    ]
);

// Silverstripe shared files
set(
    'shared_files',
    [
        '.env'
    ]
);

// Setup dev server deployment
host('__DevServer__')
    ->stage('dev')
    ->port(get('ssh_port_dev'))
    ->stage(get('branch_dev'))
    ->user(get('deploy_dev_user'))    
    ->set('deploy_path', '/path/to/{{application}}')
    ->identityFile('~/.ssh/id_rsa');

// Setup live server deployment
host('__LiveServer__')
    ->stage('live')
    ->port(get('ssh_port_live'))
    ->stage(get('branch_live'))
    ->user(get('deploy_live_user'))
    ->set('deploy_path', '/path/to/{{application}}')
    ->identityFile('~/.ssh/id_rsa');

// Disable composer --no-dev on dev
task(
    'composer:config',
    function () {
        $stage = Context::get()->getHost()->getConfig()->get('stage');

        if ($stage == "dev") {
            set(
                'composer_options',
                '{{composer_action}} --verbose --no-progress --no-interaction --optimize-autoloader'
            );
        }
    }
);

before('deploy:vendors', 'composer:config');

// Reload PHP-FPM
task(
    'reload:php-fpm',
    function () {
        run('sudo /bin/systemctl restart php74-fpm.service');
    }
);

// Purge Cache
task(
    'silverstripe:purge-cache',
    function () {
        run('rm -fR ~/{{application}}/shared/silverstripe-cache/*');
    }
);

after('deploy', 'reload:php-fpm');
after('deploy', 'silverstripe:purge-cache');

// Tasks
// Thanks to @kinglozzer / BigFork for this!
desc('Populate .env file');
task(
    'silverstripe:create_dotenv',
    function () {
        $envPath = "{{deploy_path}}/shared/.env";
        if (test("[ -f {$envPath} ]")) {
            return;
        }

        $dbServer = ask('Please enter the database server', 'localhost');
        $dbUser = ask('Please enter the database username');
        $dbPass = str_replace("'", "\\'", askHiddenResponse('Please enter the database password'));
        $dbName = ask('Please enter the database name', get('application'));
        $dbPrefix = Context::get()->getHost()->getConfig()->get('stage') === 'stage' ? '_stage_' : '';
        $baseURL = ask('Please enter the baseURL');
        $type = Context::get()->getHost()->getConfig()->get('stage');

        $contents = <<<ENV
SS_DATABASE_CLASS='MySQLPDODatabase'
SS_DATABASE_USERNAME='{$dbUser}'
SS_DATABASE_PASSWORD='{$dbPass}'
SS_DATABASE_SERVER='{$dbServer}'
SS_DATABASE_NAME='{$dbName}'
SS_DATABASE_PREFIX='{$dbPrefix}'
SS_BASE_URL='{$baseURL}'
SS_ENVIRONMENT_TYPE='{$type}'
ENV;

        $command = <<<BASH
cat >{$envPath} <<EOL
$contents
EOL
BASH;

        run("$command");
    }
)->setPrivate();

before('deploy:vendors', 'silverstripe:create_dotenv');
