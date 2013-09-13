// JavaScript Document

function autoResizepic()
{
var picTarget=document.getElementById("fontzoom").getElementsByTagName("img");
if(picTarget){
if(picTarget[0].width>600){picTarget[0].height=picTarget[0].height*600/picTarget[0].width;picTarget[0].width=600;} 
if(parseInt(picTarget[0].style.width)>600){picTarget[0].style.height=parseInt(picTarget[0].style.height)*600/parseInt(picTarget[0].style.width);picTarget[0].style.width=600+"px";} 
}
}