/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	 config.language = 'en';
	// config.uiColor = '#AADC6E';
	
	config.extraPlugins = 'MediaEmbed';
	
	// config.extraPlugins = 'autosave';
	// config.autosaveTargetUrl = 'http://life.dev/cron/autosave.php';
	
	config.toolbar = 'Base';
 
	config.toolbar_Base =
	[
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
		{ name: 'insert', items : [ 'Image','MediaEmbed','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'
                 ,'Iframe' ] },
                '/',
		{ name: 'styles', items : [ 'Styles','Format' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'tools', items : [ 'Source', 'Autosave' ] }
	];
	
	config.toolbar = 'Simple';
 
	config.toolbar_Simple =
	[
		{ name: 'styles', items : [ 'Scayt' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','-','TextColor', 'Format','-','RemoveFormat', 'Autosave' ] },
	];
};
