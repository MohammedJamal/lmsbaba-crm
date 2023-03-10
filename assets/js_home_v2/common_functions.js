function checkDate(m,d,y)
{
   try { 

      // create the date object with the values sent in (month is zero based)
      var dt = new Date(y,m-1,d,0,0,0,0);

      // get the month, day, and year from the object we just created 
      var mon = dt.getMonth() + 1;
      var day = dt.getDate();
      var yr  = dt.getYear() + 1900;

      // if they match then the date is valid
      if ( mon == m && yr == y && day == d )
         return true; 
      else
         return false;
   }
   catch(e) {
      return false;
   }
}

//====================================================================
// Get Image Preview
function GetImagePreview(input,displayDiv)
{    
   if (input.files && input.files[0]) 
   {
      var reader = new FileReader();
      reader.onload = function (e) {
        var strHtml = '<img src="'+e.target.result+'" >'; 
        $('#'+displayDiv).html(strHtml);  
      };
      reader.readAsDataURL(input.files[0]);
   }
}
// Get Image Preview
//====================================================================

//====================================================================
// Get upload file Preview
function show_upload_filename(fileId,showDivId) 
{
    var name = document.getElementById(fileId); 
    $("#"+showDivId).html('');
    if(name.files.length>0)
    {        
        var str='';
        for(i=0;i<name.files.length;i++)
        {
          // alert('Selected file: ' + name.files.item(0).name);
          // alert('Selected file: ' + name.files.item(0).size);
          // alert('Selected file: ' + name.files.item(0).type);
          str +=name.files.item(i).name+' ,';
        }
        str = str.slice(0, -1);
        $("#"+showDivId).html(str);
    }

};
// Get upload file Preview
//====================================================================

//====================================================================
// Get email validate
function is_email_validate(email) 
{
    var filter = /^([a-zA-Z0-9_\-])+(\.([a-zA-Z0-9_\-])+)*@((\[(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5]))\]))|((([a-zA-Z0-9])+(([\-])+([a-zA-Z0-9])+)*\.)+([a-zA-Z])+(([\-])+([a-zA-Z0-9])+)*))$/;
    return filter.test(email);
}
// Get email validate
//====================================================================

//====================================================================
// Get password format validate
function chk_password_format(password)
{
  return /^[A-Za-z0-9\d=!\!@#$%]*$/.test(password) // consists of only these
       && /[a-z]/.test(password) // has a lowercase letter
       && /[A-Z]/.test(password) // has a uppercase letter
       && /\d/.test(password) // has a digit  
       && /[!@#$%]/.test(password) // has a uppercase letter
}
// Get password format validate
//====================================================================

function fn_alphabets_only(evt) 
{
    if (!/^[a-zA-Z]*$/g.test(evt)) 
    {     
        return false;
    } 
    else
    {
        return false;
    }
}

function age_diff(birthday) 
{
    var now = new Date();
    var past = new Date(birthday);
    var nowYear = now.getFullYear();
    var pastYear = past.getFullYear();
    var age = nowYear - pastYear;

    return age;
}

function isUrl(url) 
{
    var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    if(!regexp.test(url)) {
      return false;
    }
    else
    {
      return true;
    }

    
}

$(document).ready(function(){
 



 $('.dob_format').bind('keypress', function (e) {
        var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right
        var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
        var ret = ((keyCode >= 47 && keyCode <= 57) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
        return ret;
    });


  $('.alphanumeric').bind('keypress', function (e) {
        var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right
        var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
        var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
        return ret;
    });

  
   //====================================================================
  // no space check
  // $('.no_space').keypress(function( e ) {
  //      if(e.which === 32){
  //        return false;
  //      }
  //   });
  // no space check
  //====================================================================

  //====================================================================
  // Namutal number
  $('.only_natural_number').keyup(function(e)
  { 
       if (/\D/g.test(this.value))
          {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
          }                 
  });
  // Namutal number
  //====================================================================


  //====================================================================
  // Alphabate only
  $(".alphabets_only").keypress(function(event){
        var inputValue = event.which;
        //Allow letters, white space, backspace and tab. 
        //Backspace ASCII = 8 
        //Tab ASCII = 9 
        if (!(inputValue >= 65 && inputValue <= 123)
            && (inputValue != 32 && inputValue != 0)
             && (inputValue != 48 && inputValue != 8)
            && (inputValue != 9)){
                event.preventDefault(); 
        }
        console.log(inputValue);
    });

  
  // Alphabate only
  //====================================================================



  //====================================================================
  // Namutal number and first letter not zero
  $('.only_natural_number_noFirstZero').keyup(function(e)
  { 
      var val = $(this).val()
      var reg = /^0/gi;
      if (val.match(reg)) {
          $(this).val(val.replace(reg, ""));
          // alert("Please phone number first character bla blaa not 0!");
          // $(this).mask("999 999-9999");
      }
      else
      {
          if (/\D/g.test(this.value))
          {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
          }
      }          
  });
  // Namutal number and first letter not zero
  //====================================================================

  //====================================================================
  // integer / float number
  // $('.double_digit').keypress(function (event) {
  //           return isNumber(event, this)
  // });

  $(".double_digit").keydown(function(e) {
        debugger;
        var charCode = e.keyCode;
        if (charCode != 8) {
            //alert($(this).val());
            if (!$.isNumeric($(this).val()+e.key)) {
                return false;
            }
        }
    return true;
  });

// $('.double_digit').keyup(function(event)
// {

   
//     if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) 
//           return true;

//      else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
//           event.preventDefault();
          
// });
  // integer / float number
  //====================================================================


  

  $('.chk_seo_url').bind('keypress', function (e) {
        var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right
        
        var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
        var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) 
          || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode) || keyCode==45 || keyCode==95);
        return ret;
    });

    

    $(document).on("focusout",".chk_postcode_validate",function(e){
        var base_URL= $("#base_url").val();
        var val_obj= $(this);
        var zoneObj= $(this).attr("data-zoneID");
        var zone_id=$("#"+zoneObj).val();
        var errorDiv=$(this).attr("data-showError");
        var address_id=$(this).attr("data-addressID");
        var data = "postcode="+val_obj.val()+"&zone_id="+zone_id;

        $.ajax({
              url: base_URL+"checkout/chk_postcode_logic_ajax",
              data: data,
              cache: false,
              method: 'POST',
              dataType: "html",
               beforeSend: function( xhr ) {
                  //$("#preloader").css('display','block');
              },
             success:function(res){ 
                 result = $.parseJSON(res);
                 //$(".preloader").hide();                
                 if(result.msg=='success')
                 {
                    $("#"+errorDiv).html('');
                    $("#address_submit_btn"+address_id).attr("disabled",false);
                 }   
                 else
                 {
                    $("#"+errorDiv).html('Your pincode/postcode not matching with zone.');
                    //val_obj.focus();
                    $("#address_submit_btn"+address_id).attr("disabled",true);
                 }         
             },
             complete: function(){
              //$("#preloader").css('display','none');
             },
             error: function(response) {
              //alert('Error'+response.table);
              }
        });
    });


});



function page_redirect(url)
{
  window.location = url;
}

$(document).ready(function(){
    
});

// THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
function isNumber(evt, element) {

    var charCode = (evt.which) ? evt.which : event.keyCode

    if (
        (charCode != 45 || $(element).val().indexOf('-') != -1) &&      // ???-??? CHECK MINUS, AND ONLY ONE.
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // ???.??? CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;

    return true;
} 


