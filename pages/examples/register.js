$(function()
{
    $('form').submit((event) =>
    {
        event.preventDefault();

        var formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            check: $('#agreeTerms').is(":checked")
        }

        let retype = $('#re-password').val();
        
        if(retype === formData.password)
        {
            if((formData.name.length || formData.email.length) !== 0 && formData.password.length > 6)
            {
                if(formData.check)
                {
                    $.post("../database/register.php", formData, (data,staus,xhr) =>
                    {
                        var data = JSON.parse(data);
                        // console.log(data);
                        if(data.success)
                        {
                            window.location = "../../index.html";

                        }else
                        {
                            // show message box if validation is false
                            $('#ErrorMessage .alert').css('display', 'block');
                            $('#ErrorMessage .text-danger').text(data.errors);
                        }
                    })
                }else
                {
                    $('#ErrorMessage .alert').css('display', 'block');
                    $('#ErrorMessage .text-danger').text("Please read and accept our Terms. ");
                }
                
            }else
            {
                // show message box if fields are empty or password does not meet criteria
                $('#ErrorMessage .alert').css('display', 'block');
                $('#ErrorMessage .text-danger').text("Invalid Details!. Please fill all the fields according to the specified criteria. ");
            }
                
        }else
        {
            // error message if passwords do not match
            $('span[class="fas fa-lock"]').attr('class','fas fa-times-circle text-danger');
            $('#password').css('border', '1px solid red');
            $('#re-password').css('border', '1px solid red');
            $('#ErrorMessage .alert').css('display', 'block');
            $('#ErrorMessage .text-danger').text("Passwords do not match! ");
            setTimeout(function()
            {
                window.location.reload();
            }, 1000)
        }

    })
})

function validate(formData)
{
    if ((formData.name.length || formData.password.length || formData.email.length) == 0)
    {
        return false;
    }
    return true;
}