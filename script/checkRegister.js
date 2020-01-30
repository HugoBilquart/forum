$newNickname = $('#newNickname');
$newEmail = $('#newEmail');
$newPass = $('#newPass');
$newPassConfirm = $('#newPassConfirm');
$useConditions = $('#useConditions');

var mailPattern = new RegExp ('^[A-Za-z0-9._-]+@[a-z0-9.-]{2,}[.][a-z]{2,5}$');
var errorArea = document.getElementById('formError');


$('#checkForm').click(function(){
    $newBirthdate = $('#birth_day').val() + "/" + $('#birth_month').val() + "/" + $('#birth_year').val();
    errorArea.innerHTML = '';

    var errLoginInfo = 0;
    var errOtherInfo = 0;
    var errUseCond = 0;

    //Check nickname
    if($newNickname.val().length >= 4) {
        $.post(
            'script/checkUsername.php',
            {
                new_username : $newNickname.val()
            },

            function(data){
                if(data == 'taken'){
                    $newNickname.css('backgroundColor','DarkOrange');
                    errorArea.innerHTML += `<li>The username is taken</li>`;
                    errLoginInfo++;
                }
                else if(data == 'available') {
                    $newNickname.css('backgroundColor','lightGreen');
                }
            },
            'text'
        );
    }
    else {
        errorArea.innerHTML += `<li>Your nickname is not long enough (at least 4 caracters)</li>`;
        $newNickname.css('backgroundColor','#ff6666');
        errLoginInfo++;
    }

    //Check email address
    if ($newEmail.val() != '' && mailPattern.test($newEmail.val())) {
        $.post(
            'script/checkEmail.php',
            {
                new_email : $newEmail.val()
            },

            function(data){
                if(data == 'used'){
                    $newEmail.css('backgroundColor','Orange');
                    errorArea.innerHTML += `<li>The email address is already used<li>`;
                    errLoginInfo++;
                }
                else if(data == 'available') {
                    $newEmail.css('backgroundColor','lightGreen');
                }
            },
            'text'
        );
    }
    else {
        errorArea.innerHTML += `<li>Your email address is improper (example@domain.com)</li>`;
        $newEmail.css('backgroundColor','#ff6666');
        errLoginInfo++;
    }

    //Check passwordS
    if($newPass.val().length >= 6) {
        $newPass.css('backgroundColor','lightGreen');
    }
    else {
        $newPass.css('backgroundColor','#ff6666');
        errorArea.innerHTML += `<li>Your password isn't strong enough (at least 6 characters)</li>`;
        errLoginInfo++;
    }

    if($newPass.val().length >= 6 && $newPassConfirm.val().length >= 6) {
        if($newPass.val() != $newPassConfirm.val()) {
            $newPass.css('backgroundColor','orange');
            $newPassConfirm.css('backgroundColor','orange');
            errorArea.innerHTML += `<li>Your password and confirmation don't match (at least 6 characters)</li>`;
            errLoginInfo++;
        }
        else {
            $newPass.css('backgroundColor','lightGreen');
            $newPassConfirm.css('backgroundColor','lightGreen');
        }
    }
    else {
        $newPassConfirm.css('backgroundColor','#ff6666');
        errorArea.innerHTML += `<li>Enter your password and confirm it (at least 6 characters)</li>`;
        errLoginInfo++;
    }
    
    if($newBirthdate != '//') {
        if(!$newBirthdate.match(/^([0-2^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/)) {
            errorArea.innerHTML += `<li>Enter an entire birthday or leave it blank</li>`;
            errOtherInfo++;
        }
    }

    if($useConditions.prop('checked') == true) {
        $("#useConditionCheck").html('✔');
        $("#useConditionCheck").css('color','green');
        $("#useConditionsArea").css('borderColor','green');
    }
    else {
        $("#useConditionCheck").html('✘');
        $("#useConditionCheck").css('color','red');
        $("#useConditionsArea").css('borderColor','red');
        errorArea.innerHTML += `<li>You must accept use conditions to register</li>`;
        errUseCond++;
    }

    if(errLoginInfo > 0) {
        $('#loginCheck').html('✘');
        $('#loginCheck').css('color','red');
        $('#newLoginInfo').css('borderColor','red');
    }
    else {
        $('#loginCheck').html('✔');
        $('#loginCheck').css('color','green');
        $('#newLoginInfo').css('borderColor','green');
    }

    if(errOtherInfo > 0) {
        $('#otherCheck').html('✘');
        $('#otherCheck').css('color','red');
        $('#newOtherInfo').css('borderColor','red');
    }
    else {
        $('#otherCheck').html('✔');
        $('#otherCheck').css('color','green');
        $('#newOtherInfo').css('borderColor','green');
    }

    var errCount = errLoginInfo + errOtherInfo + errUseCond;

    if(errCount > 0) {
        $('#register_button').attr('disabled','true');
    }
    else {
        $('#register_button').removeAttr('disabled');
    }

});

$('select').change(function(){
    clearFormCheck();
});

$('input').change(function(){
    clearFormCheck();
});

$('').change(function(){
    clearFormCheck();
});

$('input').keyup(function(){
    clearFormCheck();
});

function clearFormCheck() {
    $('#register_button').attr('disabled','true');
    $('input[type="text"]').css('backgroundColor','white');
    $('input[type="password"]').css('backgroundColor','white');
    $('#formError').text('');

    $('.form-part').css('borderColor','white');
    $('.form-part i').html('');
}