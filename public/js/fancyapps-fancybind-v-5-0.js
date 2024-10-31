Fancybox.bind('[data-fancybox="gallery"]', {
// Transition effect when changing gallery items
Carousel : {
  transition: "slide"
},
// Disable image zoom animation on opening and closing
Images : {
 	zoom : false, 
 	protected : true
},
// Custom CSS transition on opening
showClass : "f-fadeIn",
//toolbar right icons definition
toolbar: true,
buttons: [
  //"zoom",
  "iterateZoom",
  //"share",
  "slideShow",
  "fullScreen",
  //"download",
  "thumbs",
  "close"
]        //
});
