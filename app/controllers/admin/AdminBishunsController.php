<?php

class AdminBishunsController extends AdminController {

	 /**
     * Bishun Model
     * @var Post
     */
	protected $bishuns;
    /**
     * Inject the models.
     * @param Bishun $bishun
     */
    public function __construct(Bishun $bishuns)
    {
        parent::__construct();
        $this->bishuns = $bishuns;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		//list the bishuns available
		// Title
		$title = "笔顺管理";

		// Grab all the blog posts
		$bishuns = $this->bishuns;

		// Show the page
		return View::make('admin/bishuns/index', compact('bishuns', 'title'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
        // Title
        $title = "新建笔顺";

        // Show the page
        return View::make('admin/bishuns/create', compact('title'));
	}
	/**
	 * process the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function postCreate()
	{

	    if (Input::hasFile('filename')){
	        $file= Input::file('filename');
	        // dd(app_path().'/storage/uploaded/','uploaded.xxx');
	        $destfile = time().'_'.rand(1,10).'.'.$file->getClientOriginalExtension();
	        $destabsolutefile = app_path().'/storage/uploaded/';
	        $file->move($destabsolutefile,$destfile);
	        $bishun = new Bishun;
	        $bishun->hanzi = Input::get('hanzi');
	        $bishun->filename = $destfile;
	        $bishun->relatedwords = Input::get('relatedwords');
	        $bishun->save();    
	        // return [
	        //     'path'=> $file->getRealPath(),
	        //     'size'=> $file->getSize(),
	        //     'mime'=> $file->getMimeType(),
	        //     'name'=> $file->getClientOriginalName(),
	        //     'extension'=> $file->getClientOriginalExtension()
	        // ];
	        // Title
	        $title = "新建笔顺";
	        
	        // Show the page
	        return View::make('admin/bishuns/create', compact('title'))->with('success', Lang::get('admin/blogs/messages.create.success'));
	    }        
	}
	/**
	 * show a form to edit resource in storage.
	 *
	 * @return Response
	 */
	public function getEdit($bishun)
	{
		// Title
        $title = "更改笔顺信息";
        $bishunsModel = Bishun::where('hanzi','=',$bishun)->get();
        // Show the page
        return View::make('admin/bishuns/bishunedit', compact('bishunsModel', 'title'));
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postEdit($bishun)
	{
		// Declare the rules for the form validation
		$rules = array(
		    'hanzi'   => 'required',
		    'filename' => 'required',
		    'relatedwords' => 'required|min:2'
		);
		$validator = Validator::make(Input::all(), $rules);

		// Check if the form validates with success
		if ($validator->passes())
		{
		    // $this->bishuns->hanzi            = Input::get('hanzi');
			$bishuntemp = Bishun::wherehanzi(Input::get('hanzi'))->first();
		    if($bishuntemp->update(array('relatedwords'=>Input::get('relatedwords'),'filename'=>Input::get('filename'))));
		    {
		        // Redirect to the new blog post page
		        return Redirect::to('admin/bishuns/' . $bishuntemp->hanzi . '/edit')->with('success', Lang::get('admin/blogs/messages.update.success'));
		    }

		    // Redirect to the blogs post management page
		    return Redirect::to('admin/bishuns/' . $bishuntemp->hanzi . '/edit')->with('error', Lang::get('admin/blogs/messages.update.error'));
		}

		// Form validation failed
		return Redirect::to('admin/bishuns/' . $bishun . '/edit')->withInput()->withErrors($validator);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDelete($bishun)
	{
		// Title
        $title = Lang::get('admin/bishuns/title.blog_delete');

        // Show the page
        return View::make('admin/bishuns/delete', compact('bishun', 'title'));

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $post
	 * @return Response
	 */
	public function postDelete($bishun)
	{
	    // Declare the rules for the form validation
	    $rules = array(
	        // 'id' => 'required|integer'
	    );
	    // Validate the inputs
	    $validator = Validator::make(Input::all(), $rules);

	    // Check if the form validates with success
	    if ($validator->passes())
	    {
	    	var_dump(gettype($bishun));
	    	var_dump(gettype(Input::get('hanzi')));
	    	$bishuntodelete = Bishun::wherehanzi($bishun)->first();
	    	$bishuntodelete->delete();
	        // Was the bishun deleted?
	        $bishunfound = Bishun::wherehanzi($bishun);
	        if(empty($bishunfound))
	        {
	            // Redirect to the bishun management page
	            return Redirect::to('admin/bishuns')->with('success', "笔顺删除成功！");
	        }
	    }
	    // There was a problem deleting the bishun
	    return Redirect::to('admin/bishuns')->with('error', "笔顺删除错误，请重试");
	}	

	/**
     * Show a list of all the bishun formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $bishuns = Bishun::select(array('bishuns.id', 'bishuns.hanzi', 'bishuns.relatedwords','bishuns.filename', 'bishuns.created_at'));

        return Datatables::of($bishuns)

        // ->edit_column('hanzi', '{{ DB::table(\'hanzi\')->where(\'hanzi\', \'=\', $hanzi)->count() }}')

        ->add_column('actions', '<a href="{{{ URL::to(\'admin/bishuns/\' . $hanzi . \'/edit\' ) }}}" class="btn btn-default btn-xs iframe" >{{{ Lang::get(\'button.edit\') }}}</a>
                <a href="{{{ URL::to(\'admin/bishuns/\' . $hanzi . \'/delete\' ) }}}" class="btn btn-xs btn-danger iframe">{{{ Lang::get(\'button.delete\') }}}</a>
            ')

        ->remove_column('id')

        ->make();
    }

}