$(function()
{
    $('form').submit((event) => 
    {
        event.preventDefault();

        var formData = {
            email: $('#email').val(),
            password: $('#password').val(),
            check: $('#remember').is(':checked')
        }  

        if(validate(formData.email) && validate(formData.password))
        {
            $.post("pages/database/index.php", formData, (data,status, xhr) =>
            {
                var data = JSON.parse(data);
                // console.log(data); // debugging purposes
                if(data.auth)
                {
                    if(formData.check)
                    {
                        localStorage.setItem('username', data.name);
                    }else
                    {
                        sessionStorage.setItem('username', data.name);
                    }
                    window.location = "pages/examples/projects.php";
                }else
                {
                    $('#errorBox').css('display', 'block');
                    $('#errorBox .text-danger').text(data.errors);
                    window.setTimeout(function()
                    {
                        $('#errorBox').css('display', 'none');
                    }, 1000);
                }
            })
        }else
        {
            $('span[class="fas fa-envelope"]').attr('class','fas fa-times-circle text-danger');
            $('#email').css("border", '1px solid red');
            $('span[class="fas fa-lock"]').attr('class', 'fas fa-times-circle text-danger');
            $('#password').css('border', '1px solid red');
        }
    })
})

function validate(data)
{
    if(data.length == 0)
    {
        return false;
    }
    return true;
}