/**
 * Editing done from line 864
 */


function validate(arr)
{
    for(let elem of arr)
    {
        if ( elem.length == 0 || elem == 0)
        {
            return false;
        }
    }
    return true;
}

$(document).ready(function()
{
    $('#submit').on('click', () =>
    {
        var formData = {
            flag: "insert",
            name: $('#inputName').val(),
            desc: $('#inputDescription').val(),
            status: $('#inputStatus').val(),
            client: $('#inputClientCompany').val(),
            leader: $('#inputProjectLeader').val(),
            budget: parseInt($('#inputEstimatedBudget').val()) || 0,
            spent: parseInt($('#inputSpentBudget').val()) || 0,
            duration: parseInt($('#inputEstimatedDuration').val()) || 0
        }
        // if validation is approved
        if (validate(Object.values(formData)))
        {
            formData['validate'] = true;
            $.post("../database/project-add.php", formData, (data, status, xhr) =>
            {
                var data = JSON.parse(data);
                // console.log(data) // debugging purposes
                if(data.success)
                {
                    $('#Modal').modal('show')
                    $('#head').attr('class', 'text-success').html("Success<i style=\"font-size: 2rem;\" class=\"bi bi-check2-circle mx-2\"</i>")
                    $('#details').attr('class', 'text-success' ).text("Records saved Successfully!");
                    setTimeout(function()
                    {
                        window.location = "./projects.php";
                    }, 1000)
                }else
                {
                    $('#Modal').modal('show')   
                    $('#head').html("<i class=\"bi bi-exclamation-lg mx-2\"</i>Error")
                    $('#details').text(data.message);
                }
            })
        }else
        {
            $('#Modal').modal('show');
            $('#head').attr('class', 'text-danger').html("<i class=\"bi bi-exclamation-lg mx-2\"></i>Error");
            $('#details').text("Please fill all the details carefully. ");
        }

    });
});
