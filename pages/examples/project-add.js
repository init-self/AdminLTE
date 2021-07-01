/**
 * Editing done from line 864
 */

// emptiness validation
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
    // on clicking the submit button
    $('#submit').on('click', () =>
    {
        // extract the variables 
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
                    // modal on successfull addition of project
                    $('#Modal').modal('show')
                    $('#head').attr('class', 'text-success').html("Success<i style=\"font-size: 2rem;\" class=\"bi bi-check2-circle mx-2\"</i>")
                    $('#details').attr('class', 'text-success' ).text("Records saved Successfully!");
                    setTimeout(function()
                    {
                        window.location = "./projects.php"; // redirect after 1 sec of showing modal
                    }, 1000)
                }else
                {
                    // Error Modal
                    $('#Modal').modal('show')   
                    $('#head').html("<i class=\"bi bi-exclamation-lg mx-2\"</i>Error")
                    $('#details').text(data.message);
                }
            })
        }else
        {
            // Error modal on emptiness
            $('#Modal').modal('show');
            $('#head').attr('class', 'text-danger').html("<i class=\"bi bi-exclamation-lg mx-2\"></i>Error");
            $('#details').text("Please fill all the details carefully. ");
        }

    });
});
