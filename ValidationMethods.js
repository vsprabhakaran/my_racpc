function VerifyAccountandValidateAccess(phpURL,AccountNumber,pfIndex,checkADMSAccountMstr,checkCloseAccount)
{
  var doesAccountExist=doPOST_Request_Validation(phpURL,AccountNumber,pfIndex,"isValidAccount");
  if(doesAccountExist)
  {
    var isUserAndLoanAccountBelongsToSameRacpc =doPOST_Request_Validation(phpURL,AccountNumber,pfIndex,"isUserAndLoanAccountBelongsToSameRacpc");
    if(checkADMSAccountMstr)
    {
      var doesAccountExistinADMS=doPOST_Request_Validation(phpURL,AccountNumber,pfIndex,"isLoanAccountInADMS");
      if(doesAccountExistinADMS)
      {
        if(isUserAndLoanAccountBelongsToSameRacpc)
        {
          if(checkCloseAccount && isLoanClosed(phpURL,AccountNumber))
          {
            alert("This Loan account is already closed.");
            return false;
          }
          else
          {
            return true;
          }
        }
        else
        {
          alert("You don't have access to other RACPC loan accounts");
          return false;
        }
      }
      else
      {
        alert("This Loan account is not yet uploaded in ADMS.");
        return false;
      }
    }
    else
    {
      if(isUserAndLoanAccountBelongsToSameRacpc)
      {
        if(checkCloseAccount && isLoanClosed(phpURL, AccountNumber))
        {
          alert("This Loan account is already closed.");
          return false;
        }
        else
        {
          return true;
        }
      }
      else
      {
        alert("You don't have access to other RACPC loan accounts");
        return false;
      }
    }
  }
  else
  {
    alert("This Loan Account does not exist.");
    return false;
  }
}
function isLoanClosed(phpURL,accNumber)
{
  return doPOST_Request_Validation(phpURL, accNumber,"", 'isLoanClosed');
}
function doPOST_Request_Validation(phpURL, accNumber,pfNo, typeCall) {
  var returnMsg = false;
  $.ajax({
    type: 'POST',
    url: phpURL,
    data: { accNo: accNumber,pfIndex:pfNo, type: typeCall },
    success: function (msg) {
      if (msg =="true") returnMsg = true;
      else  if (msg == "false") returnMsg = false;
    },
    error: function (msg) { alert("fail : " + msg); },
    async: false
  });
  return returnMsg;
}
