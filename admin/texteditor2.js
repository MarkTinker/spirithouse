<!--  
/**
 * This is a sample JavaScript file that appears on mglenn.com for the
 * Flash Text Editor. 
 *
 * Michael Glenn
 * www.mglenn.com
 *
 */
var movieName = "editor";
var secs
var timerID = null
var timerRunning = false
var delay = 1000

function searchAndReplace(Content, SearchFor, ReplaceWith) {

   var tmpContent = Content;
   var tmpBefore = new String();   
   var tmpAfter = new String();
   var tmpOutput = new String();
   var intBefore = 0;
   var intAfter = 0;

   if (SearchFor.length == 0)
	  return;


   while (tmpContent.toUpperCase().indexOf(SearchFor.toUpperCase()) > -1) {
   
	  // Get all content before the match
	  intBefore = tmpContent.toUpperCase().indexOf(SearchFor.toUpperCase());
	  tmpBefore = tmpContent.substring(0, intBefore);
	  tmpOutput = tmpOutput + tmpBefore;

	  // Get the string to replace
	  tmpOutput = tmpOutput + ReplaceWith;


	  // Get the rest of the content after the match until
	  // the next match or the end of the content
	  intAfter = tmpContent.length - SearchFor.length + 1;
	  tmpContent = tmpContent.substring(intBefore + SearchFor.length);

   }

   return tmpOutput + tmpContent;

}


function thisMovie(movieName) {
	// IE and Netscape refer to the movie object differently.
	// This function returns the appropriate syntax depending on the browser.
	if (navigator.appName.indexOf ("Microsoft") !=-1) {
		return window.testflash[movieName]
	}	else {
		return document[movieName]
	}
}

// Checks if movie is completely loaded.
// Returns true if yes, false if no.
function movieIsLoaded (theMovie) {
  // First make sure the movie's defined.
  if (typeof(theMovie) != "undefined") {
	// If it is, check how much of it is loaded.
	return theMovie.PercentLoaded() == 100;
  } else {
	// If the movie isn't defined, it's not loaded.
	return false;
  }
}

// load the editor with text
function prepare(){
		if(movieIsLoaded(thisMovie(movieName))){
			// This style matches the default editor settings and will
			// wrap default text in the Span tag using the 'Normal' id type
			addCssStyle("id", "normal", "Normal", "Arial", "12", "#000000", "normal", "normal", "none");

			// This style is to override the default link style
			addCssStyle("class", "a", "a", "Arial", "12", "#0000FF", "italic", "bold", "underline");

			addCssStyle("id", "title", "Title", "Verdana", "16", "#FF0000", "normal", "bold");
			addFont("Arial");
			addFont("Verdana");
			addFont("Times New Roman");
			importText("<p align=\"LEFT\">This is a test with a <span id=\"title\">title style.</span> Go ahead and play with me.</p>");
			//importText("Go ahead and try me out.");
			return;
		}else{
			alert("Movie is not loaded");
		}
	}

function importText(textToImport){
	thisMovie(movieName).SetVariable("editor.importText", textToImport);
	thisMovie(movieName).TCallLabel("editor", "import");
}

function importText2(textToImport){

}
function importText3(textToImport){
	thisMovie(movieName).SetVariable("editor3.importText", textToImport);
	thisMovie(movieName).TCallLabel("editor3", "import");
}
function importText4(textToImport){
	thisMovie(movieName).SetVariable("editor4.importText", textToImport);
	thisMovie(movieName).TCallLabel("editor4", "import");
}
function importText5(textToImport){
	thisMovie(movieName).SetVariable("editor5.importText", textToImport);
	thisMovie(movieName).TCallLabel("editor5", "import");
}
function importText6(textToImport){
	thisMovie(movieName).SetVariable("editor6.importText", textToImport);
	thisMovie(movieName).TCallLabel("editor6", "import");
}

function exportText(){
	thisMovie(movieName).TCallLabel("editor", "export");
	return(thisMovie(movieName).GetVariable("editor.exportText"));
}

function exportNativeText(){
	thisMovie(movieName).TCallLabel("editor", "export");
	return(thisMovie(movieName).GetVariable("editor.exportNativeText"));
}

function addCssStyle(type, name, displayname, font, size, color, style, weight, decoration){
		if(movieIsLoaded(thisMovie(movieName))){
			if(style==null)
				style="normal";
			if(weight==null)
				weight="normal";
			if(decoration==null)
				decoration="none";
			thisMovie(movieName).SetVariable("editor.importCssType", type);
			thisMovie(movieName).SetVariable("editor.importCssName", name);
			thisMovie(movieName).SetVariable("editor.importCssDisplayName", displayname);
			thisMovie(movieName).SetVariable("editor.importCssFont", font);
			thisMovie(movieName).SetVariable("editor.importCssSize", size);
			thisMovie(movieName).SetVariable("editor.importCssColor", color);
			thisMovie(movieName).SetVariable("editor.importCssStyle", style);
			thisMovie(movieName).SetVariable("editor.importCssWeight", weight);
			thisMovie(movieName).SetVariable("editor.importCssDecoration", decoration);
			thisMovie(movieName).TCallLabel("editor", "cssimport");
			return;
		}else{
			alert("Movie is not loaded");
		}
	}


function addFont(fontName){
	thisMovie(movieName).SetVariable("editor.importFontName", fontName);
	thisMovie(movieName).TCallLabel("editor", "fontimport");
	return;
}

// pull text from the editor
function process(){
	var editorText = exportText();
	//editorText = thisMovie(movieName).GetVariable("editor.fullText");
	//editorText = searchAndReplace(editorText, "&amp;", "&");
	//editorText = searchAndReplace(editorText, "&", "&amp;");
	alert(editorText);
}

// initialize a timer to check for the loading of the movie
function InitializeTimer(){
	// Set the length of the timer, in seconds
	secs = 10
	StopTheClock()
	StartTheTimer()
}

// stop the timer
function StopTheClock(){
	if(timerRunning)
		clearTimeout(timerID)
	timerRunning = false
}

// start a timer to check for the loading of the movie
function StartTheTimer(){
	if (movieIsLoaded(thisMovie(movieName)) && thisMovie(movieName).GetVariable("isLoaded")=="yes"){
		StopTheClock()
		prepare();
	}else{
		//self.status = secs
		secs = secs - .5
		timerRunning = true
		timerID = self.setTimeout("StartTheTimer()", delay)
	}
}

//-->