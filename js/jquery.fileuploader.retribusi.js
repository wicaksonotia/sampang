$(document).ready(function () {
  // enable fileuploader plugin
  $('input[name="files"]').fileuploader({
    addMore: true,
    limit: 1,
    extensions: ["jpg", "jpeg", "png"],
    editor: {
      // editor on save quality (0 - 100)
      // only for client-side resizing
      quality: 100,

      // Callback fired after saving the image in editor
      onSave: function (
        blobOrDataUrl,
        item,
        listEl,
        parentEl,
        newInputEl,
        inputEl
      ) {
        // callback will go here
      },
    },
  });
});
