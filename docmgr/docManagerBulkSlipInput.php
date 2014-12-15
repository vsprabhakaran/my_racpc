<?php
    session_start();
    if( $_SESSION["role"] != "RACPC_ADMIN" && $_SESSION["role"] != "RACPC_DM")
    {
       $_SESSION["role"] = "";
       $_SESSION["pfno"] = "";
?><meta http-equiv="refresh" content="0;URL=../login.php"><?php
    }
    else
    {

?>
<!DOCTYPE html>
<html lang="">
    <head>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="../css/inputFormStyles.css" type="text/css">
        <link rel="stylesheet" href="../css/pure-min.css">
        <script type="text/javascript" src="../jquery-latest.min.js"></script>
        <script type="text/javascript" src="../jquery-ui.min.js"></script>
        <script type="text/javascript">
            var dbURL = "../db/UserInformations.php";
            function validateFile() {
                var result = true;
                var ext = $('#file').val().split('.').pop().toLowerCase();
                if (ext != 'txt') {
                    alert('Please choose a text file.');
                    //Resetting the file element using wrap/unwrapping - refer stackoverflow.
                    $('#file').wrap('<form>').closest('form').get(0).reset();
                    $('#file').unwrap();
                    result = false;
                }
                return result;
            }
            function isUserValid(pfNumber) {
                var returnMsg = '';
                $.ajax({
                    type: 'POST',
                    url: dbURL,
                    data: { pfno: pfNumber, type: 'isValidUser' },
                    success: function (msg) {
                        if (msg != "") returnMsg = msg.replace(/["'\\]/g, "");
                        else alert("not Found");
                    },
                    error: function (msg) { alert("fail : " + msg); },
                    async: false
                });
                if (returnMsg == "true") return true;
                else return false;
            }
            function pfNumberEnter() {
                var pfNumber = $("#pfno").val();
                var result = false;
                if (pfNumber == "") {
                    nullPFNumberEnterred();
                }
                else if (isUserValid(pfNumber)) {
                    validPFNumberEnterred();
                    result = true;
                }
                else {
                    invalidPFNumberEnterred();
                }
                return result;
            }
            function nullPFNumberEnterred() {
                resetPFField();
                $('input.pfField:text').css("background-color", "");
            }
            function validPFNumberEnterred() {
                $('input.pfField:text').css("background-color", "#CCFFCC");
                $('a.pfField').css("visibility", "visible");
            }
            function invalidPFNumberEnterred() {
                resetPFField();
                $('input.pfField:text').css("background-color", "#FFC1C1");
            }
            function resetPFField() {
                $('input.pfField:text').css("background-color", "");
                $('a.pfField').css("visibility", "hidden");
            }
            function ShowUserDetails() {
                var pfNumber = $("#pfno").val();
                var popup = window.open("../UserDetailsWindow.php?pfNo=" + pfNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
                $(popup).blur(function () { this.close(); });
            }
        </script>
    </head>
    <body>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#formid').bind("keyup keypress", function (e) {
                    var code = e.keyCode || e.which;
                    if (code == 13) {
                        e.preventDefault();
                        return false;
                    }
                });
                $('#formid').bind('submit', function () {
                    var isFormValid = true;
                    if (!validateFile()) isFormValid = false;
                    if (!pfNumberEnter()) {
                        isFormValid = false;
                        $('#pfno').effect("highlight", { color: 'yellow' }, 200);
                    }
                    if ($('#reason').val().length == 0) {
                        isFormValid = false;
                        $('#reason').effect("highlight", { color: 'yellow' }, 200);
                    }
                    return isFormValid;
                });
                $('form a').click(function () {
                    ShowUserDetails();
                });
            });

         </script>
 <br><br>
        <div>
            <form id="formid" class="pure-form pure-form-aligned" action="docManagerBulkSlipResult.php" method="post" enctype="multipart/form-data">
                <div class="pure-control-group">
                    <label for="file">Choose the file</label>
                    <input id="file" type="file" name="file" onChange="validateFile()" />
                </div>
                <div class="pure-control-group">
                    <label for="pfno">Reciever/Returnee PF</label>
                    <input class="pfField" type="text" id="pfno" name="pfno" autocomplete="off" onKeyDown="if (event.keyCode == 13) pfNumberEnter()" />
                    <a  class="pfField" href="#" id="ViewDetailsURL" style="visibility: hidden">View Details</a>
                </div>
                <div class="pure-control-group">
        			<div class="pure-u-1 pure-u-md-1-3">
        				<label for="SlipType">Slip type</label>
        				<select id="SlipType" name="SlipType" class="pure-input-1-2" style="width:20%;">
        					  <option value="Inslip">In-Slip</option>
        					  <option value="Outslip">Out-Slip</option>
        				</select>
        			</div>	
        		</div>
                <div class="pure-control-group">
                    <label for="reason" > Comments</label>
                    <textarea rows="4" cols="22" name="reason" id="reason" ></textarea>
                 </div>
                <div class="pure-controls">
                    <input type="hidden" name="actionType" value="none" id="actionType"/>
                    <button class="pure-button pure-button-primary" type="submit" id="submit" name="submit">Proceed</button>
                </div>
            </form>

        </div>

    </body>
</html>
<?php
    }
?>