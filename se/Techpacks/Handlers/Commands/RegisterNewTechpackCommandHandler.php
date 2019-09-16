<?php

namespace Platform\Techpacks\Handlers\Commands;

use Illuminate\Auth\Guard;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Techpacks\Commands\AddColorwayCommand;
use Platform\Techpacks\Commands\RegisterNewTechpackCommand;
use Platform\Techpacks\Repositories\Contracts\ColorwaysRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use App\Customer;
use Platform\App\Exceptions\SeException;
use Platform\NamingEngine\Commands\GenerateStyleCodeCommand;

class RegisterNewTechpackCommandHandler implements CommandHandler
{
    protected $techpack;
    protected $auth;
    protected $commandBus;

    /**
     * @param TechpackRepository $techpack
     * @param Guard $auth
     */
	public function __construct(
		TechpackRepository $techpack,
		Guard $auth,
		DefaultCommandBus $commandBus
	) {
        $this->techpack = $techpack;
        $this->auth = $auth;
        $this->commandBus = $commandBus;
    }


    /**
     * @param RegisterNewTechpackCommand $command
     * @return mixed
     * @throws \Exception
     */
    public function handle($command)
    {
        $customer = Customer::find($command->customer_id);
        if ($customer) {
            $code = $customer->code;
            $styleCode = $this->commandBus->execute(new GenerateStyleCodeCommand($code, $command->category, $command->product));
            $command->style_code = $styleCode;
            $command->meta['styleCode'] = $styleCode; 
        } else {
             throw new SeException("Customer not found.", 404);
        }
        return $this->techpack->registerNewTechpack($command, $this->auth->user()->id);
    }
}
