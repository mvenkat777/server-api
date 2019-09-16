<?php 

namespace Platform\Users\Commands;

class UserSearchCommand 
{
	
	    /**
    * @var array
    */
    public $query;

    /**
    * @var string
    */
    public $count;

    /**
    * @var string
    */
    public $order;

    /**
    * @var string
    */
    public $from;

    /**
    * @var string
    */
    public $se;

    /**
    * @var string
    */
    public $tag;

    /**
    * @var string
    */
    public $page;

    /**
    * @var string
    */
    public $data;

    

    /**
     * @param array $data
     */
    function __construct($query, $count, $from, $order, $se,
                         $tag,  $data, $request)
    {
        $this->query = $query;
        $this->count = $count;
        $this->from = $from;
        $this->order = $order;
        $this->se = $se;
        $this->tag = $tag;
        $this->data = $data;
        $this->page = is_null($request->get('item'))? 100:$request->get('item');
    }
}