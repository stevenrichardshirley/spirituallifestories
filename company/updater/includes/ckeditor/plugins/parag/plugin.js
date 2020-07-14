CKEDITOR.plugins.add( 'parag', {
    icons: 'parag',
    init: function( editor ) {
       editor.addCommand( 'parag', {
                exec: function( editor ) {
                    var now = new Date();
                    editor.insertHtml( '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' );
                }
            });
            
            editor.ui.addButton( 'parag', {
                label: 'Inserir parágrafo',
                command: 'parag',
                toolbar: 'basicstyles'
            });    
    }
});