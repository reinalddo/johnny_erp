modules_control.js
/*
We're going to grab the handle for the HEAD element. There are multiple 
ways to do this,  but this seems the most straightforward to me. You search 
the document for the first HEAD tag and since that should be at the top of 
the file, it's good enough!
*/
var cHead = document.getElementsByTagName("head"); //This function returns a collection of elements, an array of 1 is still an array.
var hHead = cHead[0]; //Select the first element (remember, 0-indexed arrays start at 0 not 1).
function AddModule(mFileName) //Takes the Javascript file name and adds it to the document as a script
{
 var sTag = document.createElement("script"); //Create a SCRIPT tag
 sTag.setAttribute("src", mFileName); //Set the SCRIPT src=mFileName
 sTag.setAttribute("type", "text/javascript"); //set the SCRIPT type="text/javascript"
 hHead.appendChild(sTag); //Add it to your header section (parsed and run immediately)
}
