/**
 * @author lsandhu
 */
$(document).ready( function(){
			/* Hide the central modal forms */
			$("div#center_panel").hide();
			$('[class|="visitTable"]').hide();
			
			/* function to display basic, insurance, visits info according to selected button */
			var toggleDisp = function(cls) {
				$("table.basic").hide();
				$("table.insurance").hide();
				$("table.visits").hide();
				$('[class|="visitTable"]').hide();
				var panelHeight = $("table."+cls).height();
				$("#panel").css("height", panelHeight + 250);
				if ( ! cls.match(/^visitTable/) ){
					$("button").css("background", "white");
					$("button").css("font-size", "15px");
					$("button#"+cls).css("background", "#FFFFCC");
					$("button#"+cls).css("font-size", "16px");
				} 
				$("table."+cls).slideDown(600);
				
			};
			
			/* Display basic info by-default */
			toggleDisp('basic');
						
			$("button#insurance").click(function(){
				toggleDisp('insurance');
			});	
			
			$("button#basic").click(function(){
				toggleDisp("basic");
			});
				
			$("button#visits").click(function(){
				toggleDisp("visits");
			});
			
			$("input#cancel_button").click(function(){
				toggleDisp("visits");
			});
			
			/* Common function for modal window display */
			var modalWin = function(arg) {
				/* Get window height and width  */
				var maskHeight = $(document).height();
				var maskWidth = $(window).width();	
			
				/* Set height and width to mask to fill up the whole screen */
				$('#mask').css({'width':maskWidth,'height':maskHeight});
				$('#mask').css({'z-index':1});
				
				/* transition effect */     
				$('#mask').fadeIn(500);    
				$('#mask').fadeTo("slow",0.7);  
				
				/* Get the window height and width */
				var winH = ( ($(document).height())/2 )-210;
				var winW = ( ($(document).width())/2 )-275;
								
				/* set the location of popup almost halfway from the top */
				$("."+arg).css("top", ( parseInt(winH, 10) ) + "px");
				$("."+arg).css("left", ( parseInt(winW, 10) ) + "px");
				
				/* transition effect */
				$("div."+arg).fadeIn(500);	
			};
			
			
			/* individual functions for modal window display */
			$("#basic_edit").click(function(){
        		modalWin("basic_edit");
			});	
			
			$("#insurance_edit").click(function(){
        		modalWin("insurance_edit");
			});	
			
			$("#doctor_edit").click(function(){
        		modalWin("doctor_edit");
			});
			
			$(".visit_edit_pic").click(function(){
				var table_id = $(this).attr('id');
        		toggleDisp(table_id);
			});	
			
			
    		/* if mask is clicked */
			$('#mask').click(function () {
				$(this).hide();
				$("div.basic_edit").hide();
				$("div.insurance_edit").hide();
				$("div.doctor_edit").hide();      
			}); 
});
		
		
function validate_confirm_basic(f) {
			if ( f.first.value.length < 1 &&  f.last.value.length < 1 &&
				 f.phone.value.length < 1 &&  f.email.value.length < 1 &&
				 f.addr1.value.length < 1 &&  f.addr2.value.length < 1 &&
				 f.city.value.length < 1 &&  f.state.value.length < 1 &&
				 f.zip.value.length < 1 &&  f.dd.value.length < 1 && 
				 f.notes.value.length < 1 &&   f.fname.value.length < 1 &&  f.lname.value.length < 1 )
			{
					alert("NOTHING TO EDIT........!");
					return false;
			}
			else {
				var id = confirm("Follwing changes will be made to the patient information .! \n" +
						"\n New First Name:  "+ f.first.value + "\n New Last Name:  "+ f.last.value+
						"\n New Email:  "+ f.email.value + "\n New Phone:  "+ f.phone.value+
						"\n New addr line1:  "+ f.addr1.value + "\n New addr line2:  "+ f.addr2.value+
						"\n New City:  "+ f.city.value + "\n New State:  "+ f.state.value+
						"\n New Zip:  "+ f.zip.value + "\n New Pimary insh holder DOB:  "+ f.mm.value +"-"+ f.dd.value +"-"+ f.yyyy.value+
						"\n New First Name:  "+ f.fname.value + "\n New Last Name:  "+ f.lname.value+
						"\n New Notes:  "+ f.notes.value);
				if( id==true )
					return true;
				else
					return false;
			}
}
		
function validate_confirm_insurance(f) {
			return true;
}
		
		
function validate_visit_fields(f) {
			var chk1 = false;
			var chk2 = false;
			var chk3 = true;
			var chk4 = true;
			var chk5 = true;
			var msg = "RULE IS: \n ";
			if ( f.amountcharged.value.length < 1 &&  f.billdate.value.length < 1 )
				chk1 = true;
			else if(f.amountcharged.value.length > 1 &&  f.billdate.value.length >= 1)
				chk1 = true;
			else {
				chk1 = false;
				msg = msg + "Please enter BOTH 'Bill date' and 'Amount charged' or NONE.......!\n" ;
			}
			
			if ( f.chk_number.value.length < 1 &&  f.paydate.value.length < 1  &&  f.amount_paid.value.length < 1 )
				chk2 = true;
			else if ( f.chk_number.value.length > 1 &&  f.paydate.value.length >= 1  &&  f.amount_paid.value.length >= 1 )
				chk2 = true;
			else {
				chk2 = false;
				msg = msg + "Enter ALL 'Insurance Paid' , 'Check Number' and 'Payment Date' or NONE..... \n";
			}	
				
			if ( f.chk_number.value.length > 1)
				if (f.amountcharged.value.length < 1 ) {
					chk3 = false;
					msg = msg + "If you enter Insurance paid information, Please enter billing information too..\n";
				}
			else 
				chk3 = true;
			
			if (f.paydate.value.length > 0 && f.billdate.value.length > 0 && f.orig_dos.value.length > 0) {
				var service_date = new Date(f.orig_dos.value);
				var billing_date = new Date(f.billdate.value);
				var pay_day = new Date(f.paydate.value);
				if ( pay_day >= billing_date && billing_date >= service_date )
					chk4 = true; 
				else {
					msg = msg + "'Service Date', 'Billing Date' and 'Pay Date' are NOT in correct order..\n";
					chk4 = false;
				}
			}
			
			if (f.amountcharged.value.length > 0 && f.amount_paid.value.length > 0) {
				if (f.amountcharged.value < f.amount_paid.value){
					chk5 = false;
					msg = msg + "Amount paid by insurance is greater than amount billed.....!\n";
				}
			}
			
			if (chk1 && chk2 && chk3 && chk4 && chk5) return true;
			else {
				alert(msg);
				return false;
			}		
}