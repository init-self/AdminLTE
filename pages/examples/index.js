$(function()
{
    $('form').submit((event) => 
    {
        event.preventDefault(); // prevent the form from default action

        // extract the values and form the data to transport
        var formData = {
            email: $('#email').val(),
            password: $('#password').val(),
            check: $('#remember').is(':checked')
        }  

        // validations
        if(validate(formData.email) && validate(formData.password))
        {
            /**
             * Post the data to the php file and get the response
             * perform actions according to specified response like error messages and success routing
             */
            $.post("pages/database/index.php", formData, (data,status, xhr) =>
            {
                var data = JSON.parse(data); // response data
                // console.log(data); // debugging purposes
                
                // Are you allowed to Enter?
                if(data.auth)
                {
                    // should we remember you? Yes: save username in Local Storage
                    if(formData.check) 
                    {
                        localStorage.setItem('username', data.name);
                    }else
                    {
                        // else do not save
                        sessionStorage.setItem('username', data.name);
                    }

                    // redirection to projects page
                    window.location = "pages/examples/projects.php";
                }else
                {
                    // Error Messages
                    $('#errorBox').css('display', 'block');
                    $('#errorBox .text-danger').text(data.errors);
                    window.setTimeout(function()
                    {
                        $('#errorBox').css('display', 'none'); // hide the error message after 1 sec
                    }, 1000);
                }
            })
        }else
        {
            // show error icons
            $('span[class="fas fa-envelope"]').attr('class','fas fa-times-circle text-danger');
            $('#email').css("border", '1px solid red');
            $('span[class="fas fa-lock"]').attr('class', 'fas fa-times-circle text-danger');
            $('#password').css('border', '1px solid red');
        }
    })
})

// emptiness validation
function validate(data)
{
    if(data.length == 0)
    {
        return false;
    }
    return true;
}