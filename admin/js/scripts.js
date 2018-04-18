ClassicEditor
    .create( document.querySelector( '#description' ) )
    .then( editor => {
    console.log( editor );
} )
.catch( error => {
    console.error( error );
} );