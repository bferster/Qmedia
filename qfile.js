///////////////////////////////////////////////////////////////////////////////////////////////
//  QMEDIA FILE SYSTEM
///////////////////////////////////////////////////////////////////////////////////////////////
	
	
	function QmediaFile() 													// CONSTRUCTOR
	{
		qmf=this;																// Point to obj
		this.host="//qmediaplayer.com/";										// Get host
		this.email=this.GetCookie("email");										// Get email from cookie
		this.curFile="";														// Current file
		this.password=this.GetCookie("password");								// Password
		this.butsty=" style='border-radius:10px;color#666;padding-left:6px;padding-right:6px' ";	// Button styling
	}
	
	QmediaFile.prototype.Load=function() 									//	LOAD FILE
	{
		var str="<br/>"
		str+="If you want to load only projects you have created, type your email address. To load any project, leave it blank. If you want to load a private project, you will need to type in its password. The email is not required.<br>"
		str+="<br/><blockquote><table cellspacing=0 cellpadding=0 style='font-size:11px'>";
		str+="<tr><td><b>Email</b></td><td><input"+this.butsty+"type='text' id='email' size='20' value='"+this.email+"'/></td></tr>";
		str+="<tr><td><b>Password&nbsp;&nbsp;</b></td><td><input"+this.butsty+"type='password' id='password' size='20' value='"+this.password+"'/></td></tr>";
		str+="</table></blockquote><div style='font-size:12px;text-align:right'><br>";	
		str+="<button"+this.butsty+"id='logBut'>Login</button>";	
		str+="<button"+this.butsty+"id='cancelBut'>Cancel</button></div>";	
		this.ShowLightBox("Login",str);
		var _this=this;															// Save context
		
		$("#cancelBut").button().click(function() {								// CANCEL BUTTON
			$("#lightBoxDiv").remove();											// Close
			});
	
		$("#logBut").button().click(function() {								// LOGIN BUTTON
			_this.ListFiles($("#email").val());									// Get list of files
			});
	}	
	
	QmediaFile.prototype.Save=function(saveAs) 								//	SAVE FILE TO DB
	{
		var str="<br/>"
		if (saveAs)																// If save as...
			curShow=this.curFile="";											// Force a new file to be made
		str+="Type your email address. To load any project. Type in a password to protect it. Set the private checkbox if you want to make the project private only to you. <br>"
		str+="<br/><blockquote><table cellspacing=0 cellpadding=0 style='font-size:11px'>";
		str+="<tr><td><b>Email</b><span style='color:#990000'> *</span></td><td><input"+this.butsty+"type='text' id='email' size='20' value='"+this.email+"'/></td></tr>";
		str+="<tr><td><b>Password</b><span style='color:#990000'> *</span>&nbsp;&nbsp;</b></td><td><input"+this.butsty+"type='password' id='password' size='20' value='"+this.password+"'/></td></tr>";
		str+="<tr><td><b>Private?&nbsp;&nbsp;</b></td><td><input"+this.butsty+"type='checkbox' id='private'/></td></tr>";
		str+="</table></blockquote><div style='font-size:12px;text-align:right'><br>";	
		str+="<button"+this.butsty+"id='saveBut'>Save</button>";	
		str+="<button"+this.butsty+"id='cancelBut'>Cancel</button></div>";	
		this.ShowLightBox("Save project",str);
		var _this=this;															// Save context
		
		$("#saveBut").button().click(function() {								// SAVE BUTTON
			var dat={};
			_this.password=$("#password").val();								// Get current password
			_this.email=$("#email").val();										// Get current email
			var pri= $("#private").prop("checked") ? 1 : 0						// Get private
			
			if (!_this.password && !_this.email) 								// Missing both
				 return alert("Sorry, you need to add an email and password to save.");	// Quit with alert
			else if (!_this.password) 											// Missing password
				 return alert("Sorry, you need to add a password to save.");	// Quit with alert
			else if (!_this.email) 												// Missing email
				 return alert("Sorry, you need to add an email to save.");		// Quit with alert

			_this.SetCookie("password",_this.password,7);						// Save cookie
			_this.SetCookie("email",_this.email,7);								// Save cookie
			$("#lightBoxDiv").remove();											// Close
			var url=_this.host+"saveshow.php";									// Base file
			dat["id"]=curShow;													// Add id
			dat["email"]=_this.email;											// Add email
			dat["password"]=_this.password;										// Add password
			dat["private"]=+pri;												// Add private
			dat["script"]="LoadShow("+JSON.stringify(curJson,null,'\t')+")";	// Add jsonp-wrapped script
			if (curJson.title)													// If a title	
					dat["title"]=curJson.title;									// Add title
				$.ajax({ url:url,dataType:'text',type:"POST",crossDomain:true,data:dat,  // Post data
				success:function(d) { 			
					if (d == -1) 												// Error
				 		alert("Sorry, there was an error loading that project.");		
					else if (d == -1) 											// Error
				 		alert("Sorry, the password for this project does not match the one you supplied.");		
					else if (d == -2) 											// Error
				 		alert("Sorry, there was an error saving that project.");		
					else if (d == -3) 											// Error
				 		alert("Sorry, the password for this project does not match the one you supplied.");	
				 	else if (d == -4) 											// Error
				 		alert("Sorry, there was an error updating that project.");		
				 	else if (!isNaN(d)){										// Success if a number
				 		this.curFile=d;											// Set current file
						Sound("ding");
						}
					},
				error:function(xhr,status,error) { trace(error) },				// Show error
				});		
			});
	
		$("#cancelBut").button().click(function() {								// CANCEL BUTTON
			$("#lightBoxDiv").remove();											// Close
			});
	}
	
	QmediaFile.prototype.LoadFile=function(id) 								//	LOAD A FILE FROM DB
	{
		id=id.substr(3);														// Strip off prefix
		$("#lightBoxDiv").remove();												// Close
		var url=this.host+"loadshow.php";										// Base file
		url+="?id="+id;															// Add id
		if (this.password)														// If a password spec'd
			url+="&password="+this.password;									// Add to query line
		this.curFile=id;														// Set as current file
		$.ajax({ url:url, dataType:'jsonp', complete:function() { Sound('ding'); } });	// Get data and pass to LoadProject()
	}	
		
	QmediaFile.prototype.ListFiles=function(callback, email) 				//	LIST PROJECTS IN DB
	{
		var url=this.host+"listshow.php";										// Base file
		if (email)																// If email
			url+="?email="+email;												// Add to query line
		$.ajax({ url:url, dataType:'jsonp', complete:function() { Sound('click'); } });	// Get data and pass qmfListFiles()
	}
	
	function qmfListFiles(files)											// CALLBACK TO List()
	{
		var trsty=" style='height:20px;cursor:pointer' onMouseOver='this.style.backgroundColor=\"#acc3db\"' ";
		trsty+="onMouseOut='this.style.backgroundColor=\"#f8f8f8\"' onclick='qmf.LoadFile(this.id)'";
		qmf.password=$("#password").val();										// Get current password
		qmf.SetCookie("password",qmf.password,7);								// Save cookie
		qmf.email=$("#email").val();											// Get current email
		qmf.SetCookie("email",qmf.email,7);										// Save cookie
		$("#lightBoxDiv").remove();												// Close old one
		str="<br>Choose project to load from the list below.<br>"
		str+="<br><div style='width:100%;max-height400px;overflow-y:auto'>";
		str+="<table style='font-size:12px;width:100%;padding:0px'>";
		str+="<tr><td ><b>Title </b></td><td align=right><b>&nbsp;Date&nbsp;&&nbsp;time</b></td></tr>";
		str+="<tr><td colspan='3'><hr></td></tr>";
		for (var i=0;i<files.length;++i) 										// For each file
			str+="<tr id='qmf"+files[i].id+"' "+trsty+"><td>"+files[i].title+"</td><td align=right>"+files[i].date.substr(5,11)+"</td></tr>";
		str+="</table></div><div style='font-size:12px;text-align:right'><br>";	
		str+=" <button"+qmf.butsty+"id='cancelBut'>Cancel</button></div>";	
		qmf.ShowLightBox("Load project",str);									// Show lightbox
		
		$("#cancelBut").button().click(function() {								// CANCEL BUTTON
			$("#lightBoxDiv").remove();											// Close
			});
						
		$("#loadBut").button().click(function() {								// LOAD BUTTON
			$("#lightBoxDiv").remove();											// Close
			});
	}

///////////////////////////////////////////////////////////////////////////////////////////////
//  HELPERS
///////////////////////////////////////////////////////////////////////////////////////////////

	QmediaFile.prototype.SetCookie=function(cname, cvalue, exdays)			// SET COOKIE
	{
		var d=new Date();
		d.setTime(d.getTime()+(exdays*24*60*60*1000));
		var expires = "expires="+d.toGMTString();
		document.cookie = cname + "=" + cvalue + "; " + expires;
	}
	
	QmediaFile.prototype.GetCookie=function(cname) {						// GET COOKIE
		var name=cname+"=",c;
		var ca=document.cookie.split(';');
		for (var i=0;i<ca.length;i++)  {
		  c=ca[i].trim();
		  if (c.indexOf(name) == 0) 
		  	return c.substring(name.length,c.length);
		  }
		return "";
	}

	QmediaFile.prototype.ShowLightBox=function(title, content)				// LIGHTBOX
	{
		var str="<div id='lightBoxDiv' style='position:fixed;width:100%;height:100%;";	
		str+="background:url(images/overlay.png) repeat;top:0px;left:0px';</div>";
		$("body").append(str);														
		var	width=500;
		var x=$("#lightBoxDiv").width()/2-250;
		var y=$("#lightBoxDiv").height()/2-200;
		
		str="<div id='lightBoxIntDiv' style='position:absolute;padding:16px;width:400px;font-size:12px";
		str+=";border-radius:12px;z-index:2003;"
		str+="border:1px solid; left:"+x+"px;top:"+y+"px;background-color:#f8f8f8'>";
		str+="<img src='images/qlogo32.png' style='vertical-align:-10px'/>&nbsp;&nbsp;";								
		str+="<span style='font-size:18px;text-shadow:1px 1px #ccc'><b>"+title+"</b></span>";
		str+="<div id='lightContentDiv'>"+content+"</div>";					
		$("#lightBoxDiv").append(str);	
		$("#lightBoxDiv").css("z-index",2500);						
	}