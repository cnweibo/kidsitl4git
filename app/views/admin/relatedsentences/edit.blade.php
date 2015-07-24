@extends('admin.layouts.modal')

{{-- Content --}}
@section('content')
	<!-- Tabs -->
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
			<li><a href="#tab-meta-data" data-toggle="tab">Meta data</a></li>
		</ul>
	<!-- ./ tabs -->

	{{-- Edit yinbiao Form --}}

	<form class="form-horizontal" method="post" enctype="multipart/form-data"  action="@if (isset($relatedsentence)){{ URL::to('admin/relatedsentences/' . $relatedsentence->id . '/edit') }}@endif" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->

		<!-- Tabs Content -->
		<div class="tab-content">
			<!-- General tab -->
			<div class="tab-pane active" id="tab-general">

				<!-- yinbiao id -->
				<div class="form-group {{{ $errors->has('title') ? 'error' : '' }}}">
                    <div class="col-md-12">
                        <label class="control-label" for="id">例句ID：</label>
						<input class="form-control" type="text" name="id" id="id" value="{{{$relatedsentence->id}}}" />
						{{{ $errors->first('title', '<span class="help-block">:message</span>') }}}
					</div>
				</div>
				<!-- yinbiao text -->
				<div class="form-group {{{ $errors->has('title') ? 'error' : '' }}}">
                    <div class="col-md-12">
                        <label class="control-label" for="sentencetext">例句内容：</label>
						<input class="form-control" type="text" name="sentencetext" id="sentencetext" value="{{{$relatedsentence->sentencetext}}}" />
						{{{ $errors->first('title', '<span class="help-block">:message</span>') }}}
					</div>
				</div>
	
				<!-- word mp3 file name -->
				<div class="form-group">
					<div class="col-md-12">
						<div class="panel panel-info">
							<div class="panel-heading">
							<h3 class="panel-title">MP3</h3>
							</div>
							<div class="panel-body">
				            	{{Form::label('mp3','例句mp3文件：',['class'=>'elementblock'])}}
				            	<input name="originalmp3" class="form-inline" type="text" value={{$relatedsentence->mp3}} placeholder={{$relatedsentence->mp3}}>
				            	{{Form::file('mp3')}}
				            	{{{ $errors->first('content', '<span class="help-block">:message</span>') }}}
				            </div>
					    </div>					
					</div>
				</div> 	
				<!-- 所属单词 -->
				<div class="form-group {{{ $errors->has('title') ? 'error' : '' }}}">
					<div class="col-md-12">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">所属单词</h3>
								</div>
								<div class="panel-body">
			                        <div class="input-group" style="margin-bottom: 10px">
									      <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
									      <input type="text" class="form-control" id="wordsearch" name="wordsearch" placeholder="search">
								    </div>
			                        <label class="control-label" for="relatedword_id">所属单词:</label -->
									<select multiple size=6 name="relatedword_id[]" id="relatedword_id">
										@foreach (Relatedword::all() as $relatedword)
											<option value={{$relatedword->id}}>{{$relatedword->wordtext}}</option>
										@endforeach
										
									</select>
									
				            </div>
					    </div>	                      
						{{{ $errors->first('content', '<span class="help-block">:message</span>') }}}
					</div>
				</div>
			</div>
			<!-- ./ general tab -->

			<!-- Meta Data tab -->
			<div class="tab-pane" id="tab-meta-data">
				<!-- Meta Title -->
				<div class="form-group {{{ $errors->has('meta-title') ? 'error' : '' }}}">
					<div class="col-md-12">
                        <label class="control-label" for="meta-title">Meta Title</label>
						<input class="form-control" type="text" name="meta-title" id="meta-title" value="{{{ Input::old('meta-title', isset($post) ? $post->meta_title : null) }}}" />
						{{{ $errors->first('meta-title', '<span class="help-block">:message</span>') }}}
					</div>
				</div>
				<!-- ./ meta title -->

				<!-- Meta Description -->
				<div class="form-group {{{ $errors->has('meta-description') ? 'error' : '' }}}">
					<div class="col-md-12 controls">
                        <label class="control-label" for="meta-description">Meta Description</label>
						<input class="form-control" type="text" name="meta-description" id="meta-description" value="{{{ Input::old('meta-description', isset($post) ? $post->meta_description : null) }}}" />
						{{{ $errors->first('meta-description', '<span class="help-block">:message</span>') }}}
					</div>
				</div>
				<!-- ./ meta description -->

				<!-- Meta Keywords -->
				<div class="form-group {{{ $errors->has('meta-keywords') ? 'error' : '' }}}">
					<div class="col-md-12">
                        <label class="control-label" for="meta-keywords">Meta Keywords</label>
						<input class="form-control" type="text" name="meta-keywords" id="meta-keywords" value="{{{ Input::old('meta-keywords', isset($post) ? $post->meta_keywords : null) }}}" />
						{{{ $errors->first('meta-keywords', '<span class="help-block">:message</span>') }}}
					</div>
				</div>
				<!-- ./ meta keywords -->
			</div>
			<!-- ./ meta data tab -->
		</div>
		<!-- ./ tabs content -->

		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-12">
				<element class="btn-cancel close_popup">Cancel</element>
				<button type="reset" class="btn btn-default">Reset</button>
				<button type="submit" class="btn btn-success">Update</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop
@section('scripts')
	<script src="{{asset('bootstrap/js/backend.js')}}"></script>
@stop