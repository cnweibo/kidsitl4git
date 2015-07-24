<?php

class YinbiaocategoryController extends BaseController {

    /**
     * Yinbiao Model
     * @var Yinbiao
     */
    protected $yinbiao;
    /**
     * Inject the models.
     * @param Yinbiao $yinbiao
     */
    public function __construct()
    {
        parent::__construct();

        // $this->post = $post;
        // $this->user = $user;
    }
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Get all the yinbiao category
		$yinbiaocategories = Yinbiaocategory::with('yinbiao')->get();
		return View::make('site.yinbiaocategory.index',compact('yinbiaocategories'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$ybcategory = Yinbiaocategory::with('yinbiao')->find($id);
		return View::make('site.yinbiaocategory.show',compact('ybcategory'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
