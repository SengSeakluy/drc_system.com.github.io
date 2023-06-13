<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

class GenerateJWTKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:generateJWTKeys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate privateKey and publicKey for JWT';

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
     * @return int
     */
    public function handle()
    {
        $pathToPrivateKey = 'storage/jwt-private.key';
        $pathToPublicKey = 'storage/jwt-public.key';
        // when passing paths, the generate keys will to those paths
        (new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey);
        
        echo "Done";
    }
}
