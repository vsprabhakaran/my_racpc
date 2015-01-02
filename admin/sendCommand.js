deployQZ();
		function deployQZ() {
			var attributes = {id: "qz", code:'qz.PrintApplet.class', 
				archive:'qz-print.jar', width:1, height:1};
			var parameters = {jnlp_href: 'qz-print_jnlp.jnlp', 
				cache_option:'plugin', disable_logging:'false', 
				initial_focus:'false'};
			if (deployJava.versionCheck("1.7+") == true) {}
			else if (deployJava.versionCheck("1.6+") == true) {
				delete parameters['jnlp_href'];
			}
			deployJava.runApplet(attributes, parameters, '1.5');
		}	
		function isLoaded() {
			if (!qz) {
				alert('Error:\n\n\tPrint plugin is NOT loaded!');
				return false;
			} else {
				try {
					if (!qz.isActive()) {
						alert('Error:\n\n\tPrint plugin is loaded but NOT active!');
						return false;
					}
				} catch (err) {
					alert('Error:\n\n\tPrint plugin is NOT loaded properly!');
					return false;
				}
			}
			return true;
		}
		function qzDonePrinting() {
			if (qz.getException()) {
				alert('Error printing:\n\n\t' + qz.getException().getLocalizedMessage());
				qz.clearException();
				return; 
		}
		alert('Successfully sent print data to "' + qz.getPrinter() + '" queue.');
		}
	function findPrinter(name) {
		var p="ZEBRA";
		if (name) {
			p.value = name;
		}	
		if (isLoaded()) {
			//qz.findPrinter(p.value);
			qz.findPrinter("ZEBRA");
			window['qzDoneFinding'] = function() {
				//var p = document.getElementById('printer');
				var p="ZEBRA";
				var printer = qz.getPrinter();
				alert(printer !== null ? 'Printer found: "' + printer + 
					'" after searching for "' + p.value + '"' : 'Printer "' + 
					p.value + '" not found.');
				window['qzDoneFinding'] = null;
			};
		}
	}
	function printZPL() {
		findPrinter();		 
		qz.append('^XA\n');
		var num= $("#accNumber").val();
		qz.append('^FO90,90^BY4\n');
		qz.append('^BCN,160,Y,N,N\n');
		qz.append('^FD'+num+'^FS\n');	
		qz.append('^XZ\n');
		qz.print();
	}
	function qzReady() {
		// Setup our global qz object
		//window["qz"] = document.getElementById('qz');
		var title = document.getElementById("title");
		if (qz) {
			try {
				//title.innerHTML = title.innerHTML + " " + qz.getVersion();
				
			} catch(err) { // LiveConnect error, display a detailed meesage
				document.getElementById("content").style.background = "#F5A9A9";
				alert("ERROR:  \nThe applet did not load correctly.  Communication to the " + 
					"applet has failed, likely caused by Java Security Settings.  \n\n" + 
					"CAUSE:  \nJava 7 update 25 and higher block LiveConnect calls " + 
					"once Oracle has marked that version as outdated, which " + 
					"is likely the cause.  \n\nSOLUTION:  \n  1. Update Java to the latest " + 
					"Java version \n          (or)\n  2. Lower the security " + 
					"settings from the Java Control Panel.");
		  }
	  }
	}