<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Platform\App\Commanding\CommandHandler;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Carbon\Carbon;
Use App\User;
use App\Customer;
use App\Order;
use App\Line;
use App\Sample;
use App\Techpack;
use App\Task;

/**
* To send Sourceasy Weekly Digest 
*/
class WeeklyDigestCommand extends Command implements CommandHandler
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $receivers = [
    	'ankur@sourceeasy.com',
    	'jagan@sourceeasy.com',
    	'chirag@sourceeasy.com',
    	'kishan@sourceeasy.com'
    ];

    protected $flag = false;

    protected $defaultRuleBus;
    
    protected $weeklyAction;

    /**
     * @param DefaultRuleBus $defaultRuleBus             
     */
    public function __construct(DefaultRuleBus $defaultRuleBus)
    {
        $this->defaultRuleBus = $defaultRuleBus;

        parent::__construct();
    }

	public function handle($command = NULL)
	{
		dd("weekly");
		if(date('l') == 'Sunday'){
			$data = $this->getDigest();
			if($data[0]['totalNewUser'] || $data[1]['totalNewCustomer'] || $data[2]['totalNewOrder'] || $data[3]['totalNewLine']
				|| $data[4]['totalNewTechpack'] || $data[5]['totalNewSample'] || $data[6]['newTask']['totalNewTask'] 
				|| $data[6]['newTask']['totalPendingTask'] || $data[6]['newTask']['totalCompletedTask']){
				$this->flag = true;
			}
			$data = [];
			$data['digest'] = $this->getDigest();
			if($this->flag){
				$data['emailSubject'] = 'SOURCEEASY updates for the week - '.date("l, F jS");
				$data['week'] =  Carbon::parse(Carbon::today()->subWeek())->format('D, M d Y').' - '.Carbon::parse(Carbon::today())->format('D, M d Y');
				$this->defaultRuleBus->setReceiver($this->receivers)
	                    ->setItemURL("digest") 
	                    ->execute('SendWeeklyDigest', [$data]);
			}
			$collection = $this->weeklyAction->getAllWeeklyActions();
	        foreach ($collection as $data) {
	            try {
		                $data = json_decode($data->toJson());
		                $content = $data->notification->rule;
		                $this->performAction->execute($content->notifyTarget, 
		                        $content->notifyCreator, $content->notifyAdmin, [$content->categoryName,$content->ruleName], $data->notification->data, $data->receiver);
		                $this->weeklyAction->updateWeeklyActionsById($data->_id);
	            } catch(\Exception $e) {
	            
	            }
	        }
		}
		return;
	}

	public function getDigest()
	{
		$today = Carbon::today()->toDateTimeString();
		$lastWeek = Carbon::today()->subWeek()->toDateTimeString();

		/**
		 * To Get The New User
		 */
		$user = [];
		$user = $this->getNewUser($lastWeek, $today);
		

		/**
		 * To get all Customers
		 */
		$customer = [];
		$customer = $this->getNewCustomer($lastWeek, $today);

		/**
		 * To get all Orders
		 */
		$order = [];
		$order = $this->getNewOrder($lastWeek, $today);

		/**
		 * To get all New Lines
		 */
		$line = [];
		$line = $this->getNewLine($lastWeek, $today);

		/**
		 * To get all New Techpacks
		 */
		$techpack = [];
		$techpack = $this->getNewTechpack($lastWeek, $today);

		/**
		 * To get all New Samples
		 */
		$sample = [];
		$sample = $this->getNewSample($lastWeek, $today);

		/**
		 * To get task details
		 */
		$task = [];
		$task = $this->getTaskDetail($lastWeek, $today);

		return [$user, $customer, $order, $line, $techpack, $sample, $task];
	}

	public function getNewUser($from, $to)
	{
		$user = [];
		$newUser = User::whereBetween('created_at',[$from, $to])->get();
		if(count($newUser)){
			$getUser = $newUser->toArray();
			$user = ['totalNewUser' => count($getUser)];
			foreach ($getUser as $key => $value) {
				$user['data'][$key]['displayName'] = ucfirst($value['display_name']);
				$user['data'][$key]['email'] = $value['email']; 
			}
		} else {
			$user = ['totalNewUser' => 0];
		}
		return $user;
	}

	public function getNewCustomer($from, $to)
	{
		$customer = [];
		$newCustomer = Customer::whereBetween('created_at',[$from, $to])->get();
		if(count($newCustomer)){
			$getCustomer = $newCustomer->toArray();
			$customer = ['totalNewCustomer' => count($getCustomer)];
			foreach ($getCustomer as $key => $value) {
				$customer['data'][$key]['customerName'] = $value['name'];
				$customer['data'][$key]['customerCode'] = $value['code']; 
			}
		} else {
			$customer = ['totalNewCustomer' => 0];
		}
		return $customer;
	}

	public function getNewOrder($from, $to)
	{
		$order = [];
		$newOrder = Order::whereBetween('created_at',[$from, $to])->get();
		if(count($newOrder)){
			$order = ['totalNewOrder' => count($newOrder->toArray())];
		} else {
			$order = ['totalNewOrder' => 0];
		}
		return $order;
	}

	public function getNewLine($from, $to)
	{
		$line = [];
		$newLine = Line::whereBetween('created_at',[$from, $to])->get();
		if(count($newLine)){
			$line = ['totalNewLine' => count($newLine->toArray())];
		} else {
			$line = ['totalNewLine' => 0];
		}
		return $line;
	}

	public function getNewSample($from, $to)
	{
		$sample = [];
		$newSample = Sample::whereBetween('created_at',[$from, $to])->get();
		if(count($newSample)){
			$sample = ['totalNewSample' => count($newSample->toArray())];
		} else {
			$sample = ['totalNewSample' => 0];
		}
		return $sample;
	}

	public function getNewTechpack($from, $to)
	{
		$techpack = [];
		$newTechpack = Techpack::whereBetween('created_at',[$from, $to])->get();
		if(count($newTechpack)){
			$techpack = ['totalNewTechpack' => count($newTechpack->toArray())];
		} else {
			$techpack = ['totalNewTechpack' => 0];
		}
		return $techpack;
	}

	public function getTaskDetail($from, $to)
	{
		$task = [];
		$newTask = Task::whereBetween('created_at',[$from, $to])->get();
		if(count($newTask)){
			$task['newTask'] = ['totalNewTask' => count($newTask->toArray())];
		} else {
			$task['newTask'] = ['totalNewTask' => 0];
		}
		$pendingTask = Task::where('due_date','>', $to)->whereIn('status_id', [TaskHelper::getStatusId('started'),TaskHelper::getStatusId('assigned')] )->get();
		if(count($pendingTask)){
			$task['pending'] = ['totalPendingTask' => count($pendingTask->toArray())];
		} else {
			$task['pending'] = ['totalPendingTask' => 0];
		}
		$completedTask = Task::whereBetween('completion_date',[$from, $to])->get();
		if(count($completedTask)){
			$task['completed'] = ['totalCompletedTask' => count($completedTask->toArray())];
		} else {
			$task['completed'] = ['totalCompletedTask' => 0];
		}
		return $task;
	}
}