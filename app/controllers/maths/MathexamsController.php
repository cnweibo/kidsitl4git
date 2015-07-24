<?php

class MathexamsController extends \BaseController {


    /**
     * Inject the models.
     * @param User $user
     */
    public function __construct()
    {
        parent::__construct();
        // change the blade template delimiter since conflict with angular
		// Blade::setEscapedContentTags('[[', ']]');
		// Blade::setContentTags('[[[', ']]]');
    }	


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function show($examid)
	{
		$mathexam = Mathexam::findOrFail($examid);

		$mathexamExerciseIDs = $mathexam->exerciseids;
		$tablename = $mathexam->exercisetab;
		$examcreatedat = $mathexam->created_at;
		switch ($tablename) {
			case 'mathsum4exercises':
				$exerciseModel = 'Mathsum4';
				break;
			case 'mathsum2exercises':
				$exerciseModel = 'Mathsum2';
				break;
			case 'mathsum1exercises':
				$exerciseModel = 'Mathsum1';
				break;	
			case 'mathsum3exercises':
				$exerciseModel = 'Mathsum3';
				break;	
			case 'mathmultiply2exercises':
				$exerciseModel = 'Mathmultiply2';
			default:
				break;
		}
		// dd(($mathexamExerciseIDs));
		$examIDs = json_decode($mathexamExerciseIDs);
		// 注意使用DB::raw带 FIELD(id, examIDs)将会取消默认的按照value排序的方式
		$exercises = $exerciseModel::whereIn('id', $examIDs)->orderBy(DB::raw("FIELD(id,".implode(",", $examIDs).")"))->get()->toArray();
		
		return View::make('site.mathexercise.examshow',compact('exercises','examid','examcreatedat'));




	}
	/**
	 * show form to create exam on request
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('site.mathexercise.examcreate');
	}

	/**
	 * api for create exam on request
	 *
	 * @return Response
	 */
	public function create()
	{
		$mathexam = new Mathexam;
		// return View::make('site.mathexercise.examcreate');
		$digitnumbers = Input::get('mathDigitNumbers')? Input::get('mathDigitNumbers') : 4;
		$category = Input::get('mathCategory')? Input::get('mathCategory') : 'plus'; 
		$difficulty = Input::get('mathDifficulty')? Input::get('mathDifficulty') : 1;
		$quantity = Input::get('mathQuantity')? Input::get('mathQuantity'): 100;
		$exercises = [];
		$exam=[];
		$examdata = [];
		if ($category == 'plus') {
			switch ($digitnumbers) {
				case '4':
					$mathexam-> exercisetab = "mathsum4exercises";
					$exercises = Mathsum4::where('difficulty','=',$difficulty)->orderByRaw("rand() limit 0,{$quantity}")->get()->toArray();
					//保存卷子题目字典信息到试卷数据库mathexams
					for ($i=0;$i<$quantity;$i++){
						array_push($examdata,$exercises[$i]['id']);
					}	
					break;
				case '2':
					$mathexam-> exercisetab = "mathsum2exercises";
					$exercises = Mathsum2::where('difficulty','=',$difficulty)->orderByRaw("rand() limit 0,{$quantity}")->get()->toArray();
					//保存卷子题目字典信息到试卷数据库mathexams
					for ($i=0;$i<$quantity;$i++){
						array_push($examdata,$exercises[$i]['id']);
					}	
					break;
				case '1':
					$mathexam-> exercisetab = "mathsum1exercises";
					$exercises = Mathsum1::where('difficulty','=',$difficulty)->orderByRaw("rand() limit 0,{$quantity}")->get()->toArray();
					//保存卷子题目字典信息到试卷数据库mathexams
					for ($i=0;$i<$quantity;$i++){
						array_push($examdata,$exercises[$i]['id']);
					}	
					break;	
				default:
					break;
			}
		}else if ($category == 'times'){
			switch ($digitnumbers) {
				case '2':
					$mathexam-> exercisetab = "mathmultiply2exercises";
					$exercises = Mathmultiply2::where('difficulty','=',$difficulty)->orderByRaw("rand() limit 0,{$quantity}")->get()->toArray();
					//保存卷子题目字典信息到试卷数据库mathexams
					for ($i=0;$i<$quantity;$i++){
						array_push($examdata,$exercises[$i]['id']);
					}	
					break;
			
				default:
					break;
			}
		}
		$exam_exercisesrows= json_encode($examdata);
		$mathexam-> exerciseids = $exam_exercisesrows;
		$mathexam-> difficultydata = $difficulty;
		$mathexam->save();
		$mathexamID = $mathexam -> id ;	
		switch ($mathexam->exercisetab) {
			case 'mathsum1exercises':
			case 'mathsum2exercises':
			case 'mathsum3exercises':
			case 'mathsum4exercises':
				$exam["examType"] = "plus";
				break;
			case 'mathmultiply2exercises':
				$exam["examType"] = "times";
				break;
			default:
				# code...
				break;
		}
		$exam["examID"] = $mathexamID;
		$exam["examCreatedate"] = date('m/d/Y h:i:s a', time());
		$exam["examdata"] = $exercises;
		return $exam;
		// return View::make('site.mathexercise.mathsum4',compact('exercises','mathexamID'));
	}

	public function submitAnswer(){
		$examid = Input::get('examId')? Input::get('examId') : 0;
		$score =  Input::get('score')? Input::get('score') : 10000;
		$timerData = json_decode(Input::get('timerData'));
		$mathexam = Mathexam::findOrFail($examid);
		
		$timeconsumed =  (string)$timerData->hhours . "_" . (string)$timerData->mminutes . "_" . (string)$timerData->sseconds; 
		Log::info("timer data is: $timerData->sseconds  minutes is: $timerData->mminutes and final result: $timeconsumed\n\r");
		// dd($timeconsumed);
		// $mathexam ->score = $score;

		if($mathexam->update(array('score' =>$score,'timeconsumed'=>$timeconsumed)));
		{
			return "ok";
		}
	}
}
