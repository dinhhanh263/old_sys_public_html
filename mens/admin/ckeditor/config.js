/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'ja';
	// config.uiColor = '#AADC6E';
	/*config.toolbarGroups = [
		{ name: 'document',    groups: ['document'] },
		{ name: 'insert' },
    	{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
    	{ name: 'editing',     groups: [ 'find', 'selection' ] },
    	'/',
    	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
   	 	{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
   	 	{ name: 'links' },
   	 	'/',
    	{ name: 'styles' },
    	{ name: 'colors' },
	];*/
	config.toolbar = [
     	{ name: 'document',    items : [ 'NewPage','DocProps','Preview','Print' ] },
		{ name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
		{ name: 'insert',      items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
		{ name: 'links',       items : [ 'Link','Unlink'] },
		'/',
		{ name: 'styles',      items : [ 'Font','FontSize' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
		{ name: 'colors',      items : [ 'TextColor','BGColor' ] },
		{ name: 'paragraph',   items : [ 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList','-','Outdent','Indent' ] },
		{ name: 'tools',       items : [ 'Maximize' ] }
	];
	//CKEDITOR.config.uiColor = '#95E8FF';
	config.height = '300px'; //高さ
	/*config.font_names =
    'ヒラギノ角ゴ Pro W3/ヒラギノ角ゴ Pro W3, Hiragino Kaku Gothic Pro;' +
    'メイリオ/メイリオ, Meiryo;' +
    'ＭＳ Ｐゴシック/ＭＳ Ｐゴシック;' +
    'ＭＳ Ｐ明朝/ＭＳ Ｐ明朝;' +
    'ＭＳ ゴシック/ＭＳ ゴシック;' +
    'ＭＳ 明朝/ＭＳ 明朝;' +
    'MS UI Gothic/MS UI Gothic;' +
    'Arial/Arial, Helvetica, sans-serif;' +
    'Comic Sans MS/Comic Sans MS, cursive;' +
    'Courier New/Courier New, Courier, monospace;' +
    'Georgia/Georgia, serif;' +
    'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
    'Tahoma/Tahoma, Geneva, sans-serif;' +
    'Times New Roman/Times New Roman, Times, serif;' +
    'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
    'Verdana/Verdana, Geneva, sans-serif';*/
    config.font_names =

    'MS UI Gothic/MS UI Gothic;' +
    'Arial/Arial, Helvetica, sans-serif;' +
    'Comic Sans MS/Comic Sans MS, cursive;' +
    'Courier New/Courier New, Courier, monospace;' +
    'Georgia/Georgia, serif;' +
    'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
    'Tahoma/Tahoma, Geneva, sans-serif;' +
    'Times New Roman/Times New Roman, Times, serif;' +
    'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
    'Verdana/Verdana, Geneva, sans-serif';
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
};
